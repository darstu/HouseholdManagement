<?php


namespace App\Http\Controllers\RecipeManagement;


use App\Http\Controllers\Controller;
use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;


class GoutteController extends Controller
{
    public function webCrawling(Request $request)
    {
        if ($request->input('url')) {
            $url = $request->input('url');
            if (str_contains($url, 'allrecipes.com')) {
                $data = $this->allrecipesCrawling($url);
                return back()->with('urlData', $data);
            } else if (str_contains($url, 'foodnetwork.co.uk/')) {
                // $this->foodNetworkCrawling($url);
                return back();
            } else {
                return back()->withErrors(['Source not supported']);
            }
        } else {
            return back();
        }
    }

    public function allrecipesCrawling($url)
    {
        $array = array('Servings' => '', 'CookingTime' => '');
        $sourceName = 'www.allrecipes.com';

        $goutteClient = new Client();

        $crawler = $goutteClient->request('GET', $url);
        $name = $crawler->filter('h1.headline.heading-content')->first()->text();
        $description = $crawler->filter('div.recipe-summary > p')->first()->text();
        $image = $crawler->selectImage($name)->image()->getUri();

        $type = $crawler->filter('div.component.breadcrumbs > nav > ol > li.breadcrumbs__item.breadcrumbs__item--last')->last()->text();
        $type = rtrim($type, ' Chevron Right');
        $DietType = 'Unknown';
        $crawler->filter('div.recipe-meta-item')->each(function ($node, $i) use (&$array) {
            $temp = $node->text();
            if (str_contains($temp, 'Servings')) {
                $array['Servings'] = (int)trim($temp, "Servings: ");
            }
            if (str_contains($temp, 'total')) {
                $hrs = $this->stringBetween($temp, 'total: ', ' hr');
                if (strlen($hrs) == 1) {
                    $hrs = '0' . $hrs;
                } elseif (strlen($hrs) == 0) {
                    $hrs = '00';
                }
                if ($hrs == '00' && str_contains($temp, 'mins')) {
                    $mins = $this->stringBetween($temp, 'total: ', ' mins');
                } else if (str_contains($temp, 'mins')) {
                    $mins = $this->stringBetween($temp, 'hrs ', ' mins');
                } else {
                    $mins = '00';
                }
                $array['CookingTime'] = $hrs . ":" . $mins;
            }
        });
        $cookingInstructions = $crawler->filter('li.subcontainer.instructions-section-item > div.section-body')->each(function ($node) {
            return $node->text();
        });
        $ingredients = $crawler->filter('ul.ingredients-section > li > label > input')->each(function ($node) {
            $name = $node->attr('data-ingredient');
            $amount = $node->attr('data-init-quantity');
            $unit = $node->attr('data-unit');
            if ($unit == "cups" || $unit == "teaspoons" || $unit == "tablespoons" || $unit == "ounces" || $unit == "quarts" || $unit == "pounds" || $unit == "pieces") {
                $unit = substr($unit, 0, -1);
            } elseif ($unit == "dashes" || $unit == "pinches") {
                $unit = substr($unit, 0, -2);
            } elseif (str_contains($unit, 'fluid ounce')) {
                $unit = 'fluid ounce';
            } elseif (empty($unit)) {
                $unit = 'piece';
            }
            return compact('name', 'amount', 'unit');
        });
        return array(
            'url' => $url,
            'source' => $sourceName,
            'Name' => $name,
            'Description' => $description,
            'DishType' => $type,
            'DietType' => $DietType,
            'Image' => $image,
            'Instructions' => $cookingInstructions,
            'Ingredients' => $ingredients,
            'Servings' => $array['Servings'],
            'CookingTime' => $array['CookingTime']
        );
    }

    public function foodNetworkCrawling($url)
    {
        $sourceName = 'foodnetwork.co.uk';
        $goutteClient = new Client();
        $crawler = $goutteClient->request('GET', $url);
        $name = $crawler->filter('h1')->first()->text();
        $image = $crawler->filter('div.hero.static>picture>source')->first()->attr('srcset');
        $data = ['Time' => 0, 'Servings' => 0, 'Difficulty' => ''];

        $tem = $crawler->filter('ul.recipe-head>li')->each(function ($node, $i) use (&$data) {
            if (str_contains($node->filter('span')->text(), 'Time')) {
                $temp = explode(" ", $node->filter('strong')->text(), 1);
                $data['Time'] += (int)$temp[0];
            }
            if (str_contains($node->filter('span')->text(), 'Serves')) {
                $data['Servings'] = (int)$node->filter('strong')->text();
            }
            if (str_contains($node->filter('span')->text(), 'Difficulty')) {
                $data['Difficulty'] = $node->filter('strong')->text();
            }
        });
        if ($data['Time'] < 1) {
            $cookingTime = '00:00';
        } else {
            $hrs = floor($data['Time'] / 60);
            $mins = ($data['Time'] % 60);
            if (strlen($hrs) == 1) {
                $hrs = '0' . $hrs;
            } elseif (strlen($hrs) == 0) {
                $hrs = '00';
            }
            $cookingTime = $hrs . ':' . $mins;
        }

        $cookingInstructions = $crawler->filter('div.recipe-text>p')->each(function ($node) {
            return $node->text();
        });

        $ingredients = $crawler->filter('ul.ingredients-section > li > label > input')->each(function ($node) {
            $name = $node->attr('data-ingredient');
            $amount = $node->attr('data-init-quantity');
            $unit = $node->attr('data-unit');
            return compact('name', 'amount', 'unit');
        });

        $data = array(
            'url' => $url,
            'source' => $sourceName,
            'Name' => $name,
            'Description' => '',
            'DishType' => '',
            'Image' => $image,
            'Instructions' => $cookingInstructions,
            'Ingredients' => $ingredients,
            'Servings' => $array['Servings'],
            'CookingTime' => $array['CookingTime']
        );
        return back()->with('urlData', $data);
    }

    function stringBetween($string, $start, $end)
    {
        $offset = strpos($string, $start);
        $offset += strlen($start);
        $len = strpos($string, $end, $offset) - $offset;
        return substr($string, $offset, $len);
    }
}
