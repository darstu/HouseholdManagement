<?php


namespace App\Http\Controllers\RecipeManagement;


use App\Http\Controllers\Controller;
use App\Models\HouseholdResource\Home;
use App\Models\HouseholdResource\Stock_Card;
use App\Models\RecipeManagement\Category;
use App\Models\RecipeManagement\Diet_Type;
use App\Models\RecipeManagement\Dish_type;
use App\Models\RecipeManagement\Favorite_Recipe;
use App\Models\RecipeManagement\Product;
use App\Models\RecipeManagement\Product_Stock;
use App\Models\RecipeManagement\Recipe;
use App\Models\RecipeManagement\Unit;
use App\Models\ResourceManagement\Stock;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecipeListController extends Controller
{
    public function index(Request $request)
    {
        $data = Recipe::where('Visibility', 1)->orWhere([
            ['Visibility', 0],
            ['fk_User', Auth::id()]
        ])->get();
        $arr = Recipe::select('fk_Dish_type')->distinct()->get()->toArray();
        $types = Dish_type::whereIn('id_Dish_type', $arr)->get();
        $arr = Recipe::select('fk_Diet_type')->distinct()->get()->toArray();
        $dietTypes = Diet_Type::whereIn('id_Diet_type', $arr)->get();
        $houseID = $request->input('houseID');

        if ($houseID) {
            $houseName = Home::where('id_Home', $houseID)->first();
            session(['houseName' => $houseName->Name]);
            session(['houseID' => $houseID]);
        } else {
            $houseID = session('houseID');
        }
        if (session()->has('slider')) {
            session()->forget('slider');
        }
        $this->abilityToMake($data, $houseID, 0);
        $sorted = $this->recipeSorting($data);
        $recipes = collect($sorted)->paginate(10);

        return view('RecipeManagement/RecipeList', compact('recipes', 'types', 'dietTypes'));
    }

    public function createRecipeView()
    {
        $units = Unit::all();
        return view('RecipeManagement/AddRecipe', compact('units'));
    }

    private function recipeSorting($data)
    {
        return $data->sort(function ($a, $b) {
            $aTime = $a->TimeTillExpiry;
            $bTime = $b->TimeTillExpiry;
            if ($aTime == $bTime) {
                return 0;
            } elseif ($aTime > $bTime) {
                return 1;
            } else {
                if ($aTime < 0) {
                    return 1;
                }
                return -1;
            }
        })->sortBy('AbilityToMake')->values()->all();
    }

    public static function mapProductStock()
    {
        $products = Product::all();
        $stockCards = Stock_Card::where([
            'fk_Home' => session('houseID'),
            'removed' => 0
        ])->get();
        $mapping = collect();
        foreach ($products as $product) {
            foreach ($stockCards as $stock) {
                $stockName = strtolower($stock->Name);
                $productName = strtolower($product->Name);
                if (stripos($stockName, $productName) > -1 || stripos($productName, $stockName) > -1 || stripos($stockName, $productName) === 0) {
                    $productStock = Product_Stock::where('fk_Product', $product->id_Product)
                        ->where('fk_Stock_card', $stock->id_Stock_card)->first();
                    if (!$productStock) {
                        if ($mapping) {
                            $temp = $mapping->first(function ($item) use ($product, $stock) {
                                return $item['product']->id_Product == $product->id_Product &&
                                    $item['stockCard']->id_Stock_card == $stock->id_Stock_card;
                            });
                        }
                        if (!$temp) {
                            $mapping->push(['product' => $product, 'stockCard' => $stock]);
                        }
                    }
                }
            }
        }
        if ($mapping->count() > 0) {
            return view('RecipeManagement/Mapping', compact('products', 'stockCards', 'mapping'));
        } else {
            return back()->with('Warning', 'Not found any possible mappings');
        }
    }

    public function clearProductStock()
    {
        try {
            $stockCards = Stock_Card::select('id_Stock_card')->where('fk_Home', session('houseID'))->get()->toArray();
            Product_Stock::whereIn('fk_Stock_card', $stockCards)->delete();
        } catch (\Exception $e) {
            return back()->withErrors('Unexpected error');
        }
        return back()->with('success', 'Product mapping successfully cleared');

    }

    public function confirmMapping(Request $request)
    {
        $filteredProducts = array_filter($request->input('product'));
        $filteredStockCards = array_filter($request->input('stockCard'));
        foreach ($filteredProducts as $key => $product) {
            Product_Stock::Create([
                'fk_Product' => $product,
                'fk_Stock_card' => $filteredStockCards[$key]
            ]);
        }
        if ($request->input('fromRecipe')) {
            return back()->with('success', 'Mapping created');
        } else {
            return redirect()->route('RecipeList')->with('success', 'Mapping created');
        }
    }

    public static function setMissing($recipe, $ingredient, $haveTotal, $missing, $unit, $unknown, $amount)
    {
        $recipe->AbilityToMake++;
        $miss = $amount - $haveTotal;
        $missing->push(['id' => $ingredient->fk_Product, 'amount' => $miss, 'existingAmount' => $haveTotal, 'unit' => $unit, 'unknown' => $unknown]);
    }

    public static function abilityToMake($data, $homeID, $newServings)
    {
        if ($data instanceof Recipe) {
            $data = array($data);
        }
        foreach ($data as $recipe) {
            $recipe->AbilityToMake = 0;
            $recipe->TimeTillExpiry = 999;
            $missing = collect();
            $forUse = collect();
            $check = $recipe->Name;
            $recipeIngs = RecipeController::calculateMetric($recipe);

            if ($homeID === 0) {
                $recipe->AbilityToMake = -1;
                continue;
            }
            if ($newServings !== 0) {
                $recipeIngs = RecipeController::calculateServings($newServings, $recipe->Servings_count, $recipeIngs);
                $recipe->Servings_count = $newServings;
            }

            foreach ($recipeIngs as $ingredient) {
                $productStock = Product_Stock::where('fk_Product', $ingredient->fk_Product)->get();
                if ($max = $productStock->Count()) {
                    $productUnit = strtolower($ingredient->Unit->Name);
                    for ($i = 0; $i < $max; $i++) {
                        $stockCard = Stock_Card::where('id_Stock_card', $productStock[$i]->fk_Stock_card)->first();
                        $check2 = $stockCard->Name;
                        $stockUnit = strtolower($stockCard->measurement_unit);
                        if ($stockCard && $stockCard->fk_Home == $homeID) {
                            $ch = Stock::where([
                                'fk_Stock_card' => $stockCard->id_Stock_card,
                                'fk_Home' => $homeID,
                            ])->sum('quantity');
                            $stock = Stock::where([
                                'fk_Stock_card' => $stockCard->id_Stock_card,
                                'fk_Home' => $homeID,
                            ])->where('expiration_date', '>=', Carbon::now()->format('Y-m-d'));
                            $haveTotal = $stock->sum('quantity');
                            $s = $stock->orderBy('expiration_date')->first();
                            if ($s) {
                                $expiry = new DateTime($s->expiration_date);
                                $now = new Datetime("now");
                                $diff = $now->diff($expiry)->format('%a');
                                if ($now < $expiry) {
                                    if ($recipe->TimeTillExpiry > $diff) {
                                        $recipe->TimeTillExpiry = $diff;
                                    }
                                }
                            }
                            if ($productUnit === $stockUnit) {
                                if ($ingredient->Amount <= $haveTotal) {
                                    $forUse->push(['amount' => $ingredient->Amount, 'stockCardID' => $stockCard->id_Stock_card]);
                                    break;
                                } else {
                                    if ($productStock->Count() == $i + 1) {
                                        RecipeListController::setMissing($recipe, $ingredient, $haveTotal, $missing, $stockUnit, 0, $ingredient->Amount);
                                        break;
                                    }
                                }
                            } else {
                                if ($stockUnit === 'kg') {
                                    if ($productUnit === 'g') {
                                        $amount = $ingredient->Amount / 1000;
                                    } elseif ($productUnit === 'ml') {
                                        $cof = $ingredient->Product->Conversion;
                                        $amount = $ingredient->Amount;
                                        if ($cof) {
                                            $amount *= $cof->Value;
                                        }
                                        $amount = $ingredient->Amount / 1000;
                                    } else {
                                        $forUse->push(['amount' => 0, 'stockCardID' => $stockCard->id_Stock_card]);
                                        break;
                                    }

                                    if ($amount <= $haveTotal) {
                                        $forUse->push(['amount' => $amount, 'stockCardID' => $stockCard->id_Stock_card]);
                                        break;
                                    } else {
                                        if ($productStock->Count() == $i + 1) {
                                            RecipeListController::setMissing($recipe, $ingredient, $haveTotal, $missing, $stockUnit, 0, $amount);
                                            break;
                                        }
                                    }
                                } elseif ($stockUnit === 'g' && $productUnit === 'ml') {
                                    $cof = $ingredient->Product->Conversion;
                                    $amount = $ingredient->Amount;
                                    if ($cof) {
                                        $amount *= $cof->Value;
                                    }
                                    if ($amount <= $haveTotal) {
                                        $forUse->push(['amount' => $amount, 'stockCardID' => $stockCard->id_Stock_card]);
                                        break;
                                    } else {
                                        if ($productStock->Count() == $i + 1) {
                                            RecipeListController::setMissing($recipe, $ingredient, $haveTotal, $missing, $stockUnit, 0, $amount);
                                            break;
                                        }
                                    }
                                } elseif ($stockUnit === 'l' && $productUnit === 'ml') {
                                    $amount = $ingredient->Amount / 1000;
                                    if ($amount <= $haveTotal) {
                                        $forUse->push(['amount' => $amount, 'stockCardID' => $stockCard->id_Stock_card]);
                                        break;
                                    } else {
                                        if ($productStock->Count() == $i + 1) {
                                            RecipeListController::setMissing($recipe, $ingredient, $haveTotal, $missing, $stockUnit, 0, $amount);
                                            break;
                                        }
                                    }
                                } else if ($haveTotal == 0 && $ch > 0) {
                                    if ($productStock->Count() == $i + 1) {
                                        RecipeListController::setMissing($recipe, $ingredient, 0, $missing, $productUnit, 0, $ingredient->Amount);
                                        break;
                                    }
                                } elseif ($productUnit === 'dash' || $productUnit === 'cloves'
                                    || $productUnit === 'pinch' || $productUnit === 'piece' || $productUnit === 'slice') {
                                    $forUse->push(['amount' => 0, 'stockCardID' => $stockCard->id_Stock_card]);
                                    break;
                                } else {
                                    if ($productStock->Count() == $i + 1) {
                                        RecipeListController::setMissing($recipe, $ingredient, $haveTotal, $missing, $stockUnit, 0, $ingredient->Amount);
                                        break;
                                    }
                                }
                            }
                        } else {
                            if ($productStock->Count() == $i + 1) {
                                RecipeListController::setMissing($recipe, $ingredient, 0, $missing, $stockUnit, 0, $ingredient->Amount);
                                break;
                            }
                        }
                    }
                } else {
                    RecipeListController::setMissing($recipe, $ingredient, 0, $missing, $ingredient->Unit->Name, 1, $ingredient->Amount);
                    continue;
                }
            }
            $recipe->missing = $missing;
            $recipe->forUse = $forUse;
        }
    }

    public function filter(Request $request)
    {
        $arr = $request->input('type');
        $arr2 = $request->input('dietType');
        $slider = $request->input('slider');
        $Ability = $request->input('Ability');
        $visibility = $request->input('Visibility');

        if ($visibility) {
            $data = Recipe::where([
                ['Visibility', 0],
                ['fk_User', Auth::id()]
            ])->get();
        } else {
            $data = Recipe::where('Visibility', 1)->get();
        }

        if ($arr && $arr2) {
            $data = $data->whereIn('fk_Dish_type', $arr)->whereIn('fk_Diet_type', $arr2);
        } elseif ($arr) {
            $data = $data->whereIn('fk_Dish_type', $arr);
        } elseif ($arr2) {
            $data = $data->whereIn('fk_Dish_type', $arr2);
        }
        if ($slider !== '0') {
            $this->abilityToMake($data, session('houseID'), $slider);
            session(['slider' => $slider]);
        } else {
            $this->abilityToMake($data, session('houseID'), 0);
        }
        if ($Ability === 'true') {
            $temp = collect();
            foreach ($data as $recipe) {
                if ($recipe->AbilityToMake === 0) {
                    $temp->push($recipe);
                }
            }
            $data = $temp;
        }

        $arr = Recipe::select('fk_Dish_type')->distinct()->get()->toArray();
        $types = Dish_type::whereIn('id_Dish_type', $arr)->get();
        $arr = Recipe::select('fk_Diet_type')->distinct()->get()->toArray();
        $dietTypes = Diet_Type::whereIn('id_Diet_type', $arr)->get();
        $sorted = $this->recipeSorting($data);
        $recipes = collect($sorted)->paginate(10);
        $request->flash();
        if (count($recipes) < 1) {
            $Warning = 'No recipes found according to criteria';
            return view('RecipeManagement/RecipeList', compact('recipes', 'types', 'dietTypes', 'Warning'));
        }
        return view('RecipeManagement/RecipeList', compact('recipes', 'types', 'dietTypes'));
    }

    public function search(Request $request)
    {
        if ($request->input('search')) {
            $query = $request->input('search');
            $arr = Recipe::select('fk_Dish_type')->distinct()->get()->toArray();
            $types = Dish_type::whereIn('id_Dish_type', $arr)->get();
            $arr = Recipe::select('fk_Diet_type')->distinct()->get()->toArray();
            $dietTypes = Diet_Type::whereIn('id_Diet_type', $arr)->get();
            $data = Recipe::where('Name', 'LIKE', "%{$query}%")->get();
            $recipes = collect($data)->paginate(10);
            if ($data->count() > 0) {
                $this->abilityToMake($data, session('houseID'), 0);
                return view('RecipeManagement/RecipeList', compact('recipes', 'types', 'dietTypes'));
            } else {
                $Warning = 'No such recipe found';
                return view('RecipeManagement/RecipeList', compact('recipes', 'types', 'dietTypes', 'Warning'));
            }

        } else {
            return redirect()->back();
        }
    }

    public function showFavorites()
    {
        $list = Favorite_Recipe::select('fk_Recipe')->where('fk_User', Auth::id())->get()->toArray();
        $favRecipes = Favorite_Recipe::where('fk_User', Auth::id())->get();
        $data = Recipe::whereIn('id_Recipe', $list)->get();
        $recipes = collect($data)->paginate(10);
        $categories = Category::where('fk_User', Auth::id())->get();
        if (!$recipes->Count()) {
            return view('RecipeManagement/FavoritesList')->with('recipes', $recipes)->with('categories', $categories)
                ->with('Warning', 'You have not added any recipes to favorites list');
        }
        return view('RecipeManagement/FavoritesList', compact('recipes', 'categories', 'favRecipes'));

    }

    public function editCategory(Request $request)
    {
        $name = $request->input('name');
        $id = $request->input('id');
        if ($name) {
            Category::where('id_Category', $id)->update([
                'name' => $name
            ]);
        }
        return back()->with('success', 'Category name changed successfully');
    }

    public function deleteCategory($id_Category)
    {
        Favorite_Recipe::where('fk_Category', $id_Category)->update([
            'fk_Category' => null
        ]);
        Category::where('id_Category', $id_Category)->delete();
        return back()->with('success', 'Category removed successfully');
    }

    public function addCategory(Request $request)
    {
        $name = $request->input('Category');
        $id = Auth::id();
        $cat = Category::where('Name', $name)->where('fk_User', $id)->first();
        if (!$cat) {
            if (!$name) {
                return back()->withErrors('Category name can not be blank');
            }
            Category::Create([
                'Name' => $name,
                'fk_User' => $id
            ]);
        }
        return back()->with('success', 'Category created successfully');
    }

    public function addToCategory(Request $request, $id_Recipe)
    {
        try {
            Favorite_Recipe::where('fk_Recipe', $id_Recipe)
                ->where('fk_User', Auth::id())->update([
                    'fk_Category' => $request->input('id_Category')
                ]);
            return back()->with('success', 'Recipe successfully added to the category');
        } catch (\Exception $e) {
            return back()->withErrors(['Unexpected Error']);
        }
    }

    public function filterCategory($category, $check)
    {
        if ((int)$check === 0) {
            $id = null;
            $warning = 'Every recipe in your favorites list has a category';
        } else {
            $id = $category->id_Category;
            $warning = 'You have not added any recipes to category-' . $category->Name;
        }
        $list = Favorite_Recipe::select('fk_Recipe')->where([
            ['fk_User', Auth::id()],
            ['fk_Category', $id]
        ])->get()->toArray();
        $data = Recipe::whereIn('id_Recipe', $list)->get();
        $recipes = collect($data)->paginate(10);
        $categories = Category::where('fk_User', Auth::id())->get();
        if (!$recipes->Count()) {
            return view('RecipeManagement/FavoritesList')->with('recipes', $recipes)->with('categories', $categories)
                ->with('Warning', $warning);
        }
        return view('RecipeManagement/FavoritesList', compact('recipes', 'categories'));
    }
}

