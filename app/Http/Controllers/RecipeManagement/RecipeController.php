<?php


namespace App\Http\Controllers\RecipeManagement;


use App\Http\Controllers\Controller;
use App\Http\Controllers\ResourceManagement\ResourceController;
use App\Http\Controllers\ResourceManagement\ResourceListController;
use App\Http\Requests\AddRecipe;
use App\Models\HouseholdResource\Stock_Card;
use App\Models\RecipeManagement\Comment;
use App\Models\RecipeManagement\Cooking_Instruction_Step;
use App\Models\RecipeManagement\Diet_Type;
use App\Models\RecipeManagement\Dish_type;
use App\Models\RecipeManagement\Favorite_Recipe;
use App\Models\RecipeManagement\Product;
use App\Models\RecipeManagement\Product_Stock;
use App\Models\RecipeManagement\Rating;
use App\Models\RecipeManagement\Recipe;
use App\Models\RecipeManagement\Recipe_Ingredient;
use App\Models\RecipeManagement\Unit;
use App\Models\RecipeManagement\Source;
use App\Models\RecipeManagement\Unit_Conversion;
use App\Models\ResourceManagement\Stock;
use App\Models\ResourceManagement\Stock_Type;
use App\Models\ResourceManagement\Warehouse_place;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Intervention\Image\Facades\Image;

//DB_HOST=sql144.main-hosting.eu
//DB_PORT=3306
//DB_DATABASE=u546997800_HouseHold
//DB_USERNAME=u546997800_root
//DB_PASSWORD=46513200Aa

class RecipeController extends Controller
{
    public function index($id_Recipe)
    {
        $recipe = Recipe::where('id_Recipe', $id_Recipe)->first();
        $stockCards = Stock_Card::where([
            'fk_Home' => session('houseID'),
            'removed' => 0
        ])->get();
        $rateCount = $recipe->Rating->Count();
        $rate = $this->calculateRating($recipe->Rating, $rateCount);
        if (session()->has('slider')) {
            $servings = session('slider');
            RecipeListController::abilityToMake($recipe, session('houseID'), $servings);
            $recipeIng = null;
            $recipeIng = $this->calculateServings($servings, $recipe->Servings_count, $recipe->Recipe_ingredient);
            $recipe->Servings_count = $servings;
        } else {
            $recipeIng = null;
            RecipeListController::abilityToMake($recipe, session('houseID'), 0);
        }
        $metric = 0;
        return view('RecipeManagement/Recipe', compact('recipe', 'rate', 'rateCount', 'recipeIng', 'metric', 'stockCards'));
    }

    public function editView($recipe)
    {
        $tag = 'edit';
        $units = Unit::all();
        return view('RecipeManagement/AddRecipe', compact('recipe', 'tag', 'units'));
    }

    public function newVersionView($recipe)
    {
        $tag = 'newVersion';
        $units = Unit::all();
        return view('RecipeManagement/AddRecipe', compact('recipe', 'tag', 'units'));
    }

    public function rateView($recipe)
    {
        $review = Rating::firstwhere(['fk_User' => Auth::id(), 'fk_Recipe' => $recipe->id_Recipe]);
        if ($review) {
            return back()->withErrors('You have already given a review');
        } else {
            return view('RecipeManagement/RecipeReview', compact('recipe'));
        }
    }

    private function calculateRating($ratings, $count)
    {
        $rate = 0;
        if ($count) {
            foreach ($ratings as $rating) {
                $rate += $rating->Rating;
            }
            $rate = $rate / $count;
            $rate = round($rate * 2) / 2;
        }
        return $rate;
    }

    public static function calculateMetric($recipe)
    {
        $conversions = Unit_Conversion::all();
        foreach ($recipe->Recipe_ingredient as $ingredient) {
            foreach ($conversions as $conv) {
                if ($ingredient->Unit) {
                    if ($ingredient->Unit->Name == $conv->Unit_from) {
                        $ingredient->Amount = $ingredient->Amount * $conv->Value;
                        $unit = Unit::where('name', $conv->Unit_to)->first();
                        $ingredient->Unit = $unit;
                    }
                }
            }
        }
        return $recipe->Recipe_ingredient;
    }

    public function showMetric($recipe, $calc)
    {
        $stockCards = Stock_Card::where([
            'fk_Home' => session('houseID'),
            'removed' => 0
        ])->get();
        $recipeIng = $this->calculateMetric($recipe);
        if ($calc > 0) {
            $recipeIng = $this->calculateServings($calc, $recipe->Servings_count, $recipeIng);
            $recipe->Servings_count = $calc;
            RecipeListController::abilityToMake($recipe, session('houseID'), $calc);
        } elseif (session()->has('slider')) {
            $servings = (int)session('slider');
            $recipeIng = $this->calculateServings($servings, $recipe->Servings_count, $recipeIng);
            $recipe->Servings_count = $servings;
            RecipeListController::abilityToMake($recipe, session('houseID'), $servings);
        } else {
            RecipeListController::abilityToMake($recipe, session('houseID'), 0);
        }
        $rateCount = $recipe->Rating->Count();
        $rate = $this->calculateRating($recipe->Rating, $rateCount);
        $metric = 1;
        return view('RecipeManagement/Recipe', compact('recipe', 'recipeIng', 'rate', 'rateCount', 'metric','stockCards'));
    }

    public static function calculateServings($newAmount, $oldAmount, $Recipe_ingredient)
    {
        foreach ($Recipe_ingredient as $ingredient) {
            $amount = $ingredient->Amount;
            $temp = ($amount * $newAmount) / $oldAmount;
            $ingredient->Amount = round($temp, 2);
        }
        return $Recipe_ingredient;
    }

    public function showCalculatedRecipe($id_Recipe, $calc)
    {
        $stockCards = Stock_Card::where([
            'fk_Home' => session('houseID'),
            'removed' => 0
        ])->get();
        $recipe = Recipe::where('id_Recipe', $id_Recipe)->first();
        $original = Recipe::where('id_Recipe', $id_Recipe)->first();
        $recipeIng = $this->calculateServings($calc, $recipe->Servings_count, $recipe->Recipe_ingredient);
        $originalIng = $this->calculateServings($calc, $recipe->Servings_count, $original->Recipe_ingredient);
        $recipe->Servings_count = $calc;
        $rateCount = $recipe->Rating->Count();
        $rate = $this->calculateRating($recipe->Rating, $rateCount);
        RecipeListController::abilityToMake($recipe, session('houseID'), $calc);
        $metric = 0;
        return view('RecipeManagement/Recipe', compact('recipe', 'recipeIng', 'rate', 'rateCount', 'metric', 'originalIng','stockCards'));
    }

    public function autoComplete(Request $request)
    {
        if ($request->input('query')) {
            $query = $request->input('query');
            if ($request->input('check') == 'Type') {
                $data = Dish_type::where('Name', 'LIKE', "%{$query}%")->get();
            } elseif ($request->input('check') == 'DietType') {
                $data = Diet_Type::where('Name', 'LIKE', "%{$query}%")->get();
            } else {
                $data = Product::where('Name', 'LIKE', "%{$query}%")
                    ->get();
            }
            $response = array();
            foreach ($data as $item) {
                $response[] = array("value" => $item->Name, "label" => $item->Name);
            }
            echo json_encode($response);
        }
    }

    public function addToFavorites($id_Recipe)
    {
        $id = Auth::id();
        $item = Favorite_Recipe::where('fk_Recipe', $id_Recipe)->where('fk_User', $id)->withTrashed()->first();
        if ($item) {
            $item->restore();
            return Redirect::back()->with('success', 'Successfully added to favorites');
        } else {
            Favorite_Recipe::Create([
                'fk_Recipe' => $id_Recipe,
                'fk_User' => $id,
            ]);
            return Redirect::back()->with('success', 'Successfully added to favorites');
        }
    }

    public function addComment(Request $request, $id_Recipe, $id_Comment)
    {
        $text = $request->input('Text');
        $check=str_replace(' ','',$text);
        if (strlen($check) <= 1) {
            return back()->withErrors('Comment can not be empty');
        }
        if ($id_Comment == 0) {
            $id = null;
        } else {
            $id = $id_Comment;
        }
        Comment::Create([
            'Text' => $request->input('Text'),
            'Date_created' => date("Y-m-d H:i"),
            'fk_User' => Auth::id(),
            'fk_Recipe' => $id_Recipe,
            'fk_Main_Comment' => $id
        ]);
        return Redirect::back()->with('success', 'Comment succesfully saved');
    }

    public function rateSave(Request $request, $id_Recipe)
    {
        $filename = null;
        if ($request->hasFile('Image')) {
            $image = $request->file('Image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
            Image::make($image)->resize(200, 200)->save($location);
        }
        $review = Rating::firstwhere(['fk_User' => Auth::id(), 'fk_Recipe' => $id_Recipe]);
        if ($review) {
            return Redirect::route('Recipe', $id_Recipe)->withErrors('You have already given a review');
        } else {
            Rating::Create([
                'Rating' => $request->input('stars'),
                'Headline' => $request->input('Headline'),
                'Feedback' => $request->input('Feedback'),
                'Image_address' => $filename,
                'fk_Recipe' => $id_Recipe,
                'fk_User' => Auth::id()
            ]);
            if ($request->hasFile('Image')) {

            }
            return Redirect::route('Recipe', $id_Recipe)->with('success', 'You have submitted your review');
        }
    }

    public function addRecipe(AddRecipe $request, $id_Recipe)
    {
        if ($id_Recipe == 0) {
            $id = null;

        } else {
            $id = $id_Recipe;
        }
        $name = $request->input('Name');
        if ($request->input('imageSource')) {
            $filename = $request->input('imageSource');
        } else {
            $filename = $request->input('oldPhoto');
        }
        if ($request->hasFile('Image')) {
            $image = $request->file('Image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
        }
        $filteredIngredients = array_filter($request->input('inputIngredientName'));
        $filteredAmounts = array_filter($request->input('inputIngredientAmount'));
        $filteredUnits = array_filter($request->input('inputUnits'));
        $filteredInstructions = array_filter($request->input('inputInstructionName'));
        try {
            DB::beginTransaction();
            $dishType = Dish_type::where('Name', $request->input('DishType'))->first();
            if (!$dishType) {
                $dishType = Dish_type::Create([
                    'Name' => $request->input('DishType'),
                ]);
            }
            $dietType = Diet_Type::where('Name', $request->input('DietType'))->first();
            if (!$dietType) {
                $dietType = Diet_Type::Create([
                    'Name' => $request->input('DietType'),
                ]);
            }
            $recipe = Recipe::Create([
                'Name' => $name,
                'Image_Address' => $filename,
                'Description' => $request->input('Description'),
                'Difficulty' => $request->input('Difficulty'),
                'Cooking_time' => $request->input('Cooking_time'),
                'Servings_count' => $request->input('Servings_Count'),
                'fk_Dish_type' => $dishType->id_Dish_type,
                'fk_Diet_type' => $dietType->id_Diet_type,
                'fk_User' => Auth::id(),
                'fk_Main_Recipe' => $id,
                'Date_created' => date("Y-m-d"),
                'Visibility' => $request->input('optRadio')
            ]);
            if (!$request->input('hostname') == "") {
                Source::create([
                    'Name' => $request->input('hostname'),
                    'Address' => $request->input('url'),
                    'fk_Recipe' => $recipe->id_Recipe
                ]);
            }
            if (sizeof($filteredIngredients) == sizeof($filteredAmounts)) {
                $this->addIngredients($filteredIngredients, $filteredAmounts, $filteredUnits, $recipe->id_Recipe);
            } else {
                return Redirect::back()->withErrors(['Every ingredient has to have name and amount inline'])->withInput();
            }


            $max = sizeof($filteredInstructions);
            $files = $request->file('InstructionImage');

            for ($i = 0; $i < $max; $i++) {
                $tempFilename = null;
                if (!empty($files) && array_key_exists($i, $files)) {
                    $tempImage = $files[$i];
                    $tempFilename = time() . $i . '.' . $tempImage->getClientOriginalExtension();
                    $tempLocation = public_path('images/' . $tempFilename);
                    Image::make($tempImage)->resize(200, 200)->save($tempLocation);
                }
                Cooking_instruction_Step::Create([
                    'Step_Description' => $filteredInstructions[$i],
                    'Step_number' => $i + 1,
                    'Image_address' => $tempFilename,
                    'fk_Recipe' => $recipe->id_Recipe
                ]);
            }
            if ($request->hasFile('Image')) {
                Image::make($image)->resize(400, 400)->save($location);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            if (str_contains($e->getMessage(), "Undefined array key")) {
                return Redirect::back()->withErrors(['Recipe ingredients information has to be inline'])->withInput();
            }
            return Redirect::back()->withErrors(['Unexpected Error'])->withInput();
        }
        return redirect('/recipes/' . $recipe->id_Recipe);
    }

    private function addIngredients($ingredients, $amounts, $units, $id_Recipe)
    {
        foreach ($ingredients as $key => $value) {
            $amounts[$key] = round($amounts[$key], 2);

            $temp = ucfirst($value);
            $product = Product::where('Name', $temp)->first();
            if (!$product) {
                $product = Product::Create([
                    'Name' => $temp
                ]);
            }
            $unit = Unit::where('Name', $units[$key])->first();
            Recipe_Ingredient::Create([
                'Amount' => $amounts[$key],
                'fk_Unit' => $unit->id_Unit,
                'fk_Product' => $product->id_Product,
                'fk_Recipe' => $id_Recipe
            ]);
        }
    }

    public function updateRecipe(AddRecipe $request, $recipe)
    {
        $filename = $request->input('oldPhoto');
        if ($request->hasFile('Image')) {
            $image = $request->file('Image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $location = public_path('images/' . $filename);
        }

        try {
            DB::beginTransaction();
            $dishType = Dish_type::where('Name', $request->input('DishType'))->first();
            if (!$dishType) {
                $dishType = Dish_type::where('Name', $request->input('DishType'))->create([
                    'Name' => $request->input('DishType'),
                ]);
            }
            $dietType = Diet_Type::where('Name', $request->input('DietType'))->first();
            if (!$dietType) {
                $dietType = Diet_Type::Create([
                    'Name' => $request->input('DietType'),
                ]);
            }
            $recipe->update([
                'Name' => $request->input('Name'),
                'Image_Address' => $filename,
                'Description' => $request->input('Description'),
                'Difficulty' => $request->input('Difficulty'),
                'Cooking_time' => $request->input('Cooking_time'),
                'Servings_count' => $request->input('Servings_Count'),
                'fk_Dish_type' => $dishType->id_Dish_type,
                'fk_Diet_type' => $dietType->id_Diet_type,
                'Date_created' => date("Y-m-d"),
                'Visibility' => $request->input('optRadio')
            ]);
            //dd($recipe,$request->input('optRadio'));
            $this->updateIngredients($request, $recipe->id_Recipe);
            $this->updateInstructions($request, $recipe->id_Recipe);

            if ($request->hasFile('Image')) {
                Image::make($image)->resize(400, 400)->save($location);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            if (str_contains($e->getMessage(), "Undefined array key")) {
                return Redirect::back()->withErrors(['Recipe ingredients and their amounts have to be inline',
                    'Recipe ingredients and their amounts have start from 1 and be written without empty lines'])->withInput();
            }
            return Redirect::back()->withErrors(['Unexpected Error', $e->getMessage()])->withInput();
        }
        return redirect('/recipes/' . $recipe->id_Recipe);
    }

    public function updateIngredients($request, $id_Recipe)
    {
        $oldIngredients = array_filter($request->input('oldIngredientName'));
        $oldAmounts = array_filter($request->input('oldIngredientAmount'));
        $oldUnits = array_filter($request->input('oldUnits'));
        $ingredients = array_filter($request->input('inputIngredientName'));
        $amounts = array_filter($request->input('inputIngredientAmount'));
        $units = array_filter($request->input('inputUnits'));

        if (sizeof($ingredients) == sizeof($amounts)) {
            if (sizeof($oldIngredients) > sizeof($ingredients)) {
                $max = sizeof($oldIngredients);
            } else {
                $max = sizeof($ingredients);
            }
            for ($i = 0; $i < $max; $i++) {
                $flagUnnecessary = false;
                $flagEqualProducts = false;
                $flagEqualUnits = false;
                if (array_key_exists($i, $amounts)) {
                    $amounts[$i] = round($amounts[$i], 2);
                }

                if (array_key_exists($i, $ingredients)) {
                    $temp = ucfirst($ingredients[$i]);
                    $product = Product::where('Name', $temp)->first();
                    if (array_key_exists($i, $oldIngredients)) {
                        $oldProduct = Product::where('Name', $temp)->first();
                        $oldProductID = $oldProduct->id_Product;
                        if ($product == $oldProduct) {
                            $flagEqualProducts = true;
                        }
                    } else {
                        $oldProductID = null;
                    }
                    if (!$product) {
                        $product = Product::Create([
                            'Name' => $temp
                        ]);
                    }
                } else {
                    $flagUnnecessary = true;
                    $product = Product::where('Name', $oldIngredients[$i])->first();
                    $oldProductID = $product->id_Product;
                }

                if (array_key_exists($i, $units)) {
                    $unit = Unit::where('Name', $units[$i])->first();
                    if (array_key_exists($i, $oldIngredients)) {
                        $oldUnit = Unit::where('Name', $oldUnits[$i])->first();
                        $oldUnitID = $oldUnit->id_Unit;
                        if ($unit == $oldUnit) {
                            $flagEqualUnits = true;
                        }
                    } else {
                        $oldUnitID = null;
                    }
                    $newUnitID = $unit->id_Unit;
                } else {
                    $oldUnit = Unit::where('Name', $oldUnits[$i])->first();
                    $oldUnitID = $oldUnit->id_Unit;
                }

                if ($flagEqualUnits && $flagEqualProducts && $flagEqualUnits && (float)$oldAmounts[$i] === (float)$amounts[$i]) {
                    continue;
                } else {
                    if (is_null($oldProductID)) {
                        Recipe_Ingredient::Create([
                            'Amount' => $amounts[$i],
                            'fk_Unit' => $newUnitID,
                            'fk_Product' => $product->id_Product,
                            'fk_Recipe' => $id_Recipe
                        ]);
                    } else {
                        $item = Recipe_Ingredient::where([
                            'Amount' => $oldAmounts[$i],
                            'fk_Unit' => $oldUnitID,
                            'fk_Product' => $oldProductID,
                            'fk_Recipe' => $id_Recipe
                        ])->first();
                        if ($flagUnnecessary) {
                            $item->delete();
                        } else {
                            $item->update([
                                'Amount' => $amounts[$i],
                                'fk_Unit' => $newUnitID,
                                'fk_Product' => $product->id_Product
                            ]);
                        }
                    }
                }
            }
        } else {
            return Redirect::back()->withErrors(['Every ingredient has to have name and amount inline'])->withInput();
        }
    }

    public function updateInstructions($request, $id_Recipe)
    {
        $Instructions = array_filter($request->input('inputInstructionName'));
        $oldInstructions = array_filter($request->input('oldInstructions'));
        $deletedCount = 0;
        if ($request->File('InstructionImage')) {
            $files = $request->file('InstructionImage');
        } else {
            $files = array();
        }

        if (!empty($request->input('oldInstructionsImage'))) {
            $oldFiles = $request->input('oldInstructionsImage');
        } else {
            $oldFiles = array();
        }
        if (sizeof($oldInstructions) > sizeof($Instructions)) {
            $max = sizeof($oldInstructions);
        } else {
            $max = sizeof($Instructions);
        }
        for ($i = 0; $i < $max; $i++) {
            $flagEquals = false;
            $flagUnnecessary = false;

            if (array_key_exists($i, $files)) {
                $tempImage = $files[$i];
                $tempFilename = time() . $i . '.' . $tempImage->getClientOriginalExtension();
                $tempLocation = public_path('images/' . $tempFilename);
                Image::make($tempImage)->resize(200, 200)->save($tempLocation);
                if (array_key_exists($i, $oldFiles)) {
                    $oldFileName = $oldFiles[$i];
                } else {
                    $oldFileName = null;
                }
            } elseif (array_key_exists($i, $oldFiles)) {
                $oldFileName = $oldFiles[$i];
                $tempFilename = null;
            } else {
                $tempFilename = null;
                $oldFileName = null;
            }

            if (array_key_exists($i, $Instructions)) {
                $temp = ucfirst($Instructions[$i]);
                $step = Cooking_Instruction_Step::where([
                    'Step_description' => $temp,
                    'fk_Recipe' => $id_Recipe,
                    'Step_number' => $i + 1
                ]);

                if (array_key_exists($i, $oldInstructions)) {
                    $oldStep = Cooking_Instruction_Step::where([
                        'Step_description' => $oldInstructions[$i],
                        'fk_Recipe' => $id_Recipe,
                        'Step_number' => $i + 1,
                    ]);
                    if ($oldStep->first() == $step->first()) {
                        $flagEquals = true;
                    }
                } else {
                    $oldStep = null;
                }
            } elseif (array_key_exists($i, $oldInstructions)) {
                $flagUnnecessary = true;
                $oldStep = Cooking_Instruction_Step::where([
                    'Step_description' => $oldInstructions[$i],
                    'fk_Recipe' => $id_Recipe,
                    'Step_number' => $i + 1,
                ]);
            }
            if ($flagEquals && is_null($tempFilename)) {
                continue;
            } else {
                if (is_null($oldStep)) {
                    Cooking_instruction_Step::Create([
                        'Step_Description' => $Instructions[$i],
                        'Step_number' => $i + 1 - $deletedCount,
                        'Image_address' => $tempFilename,
                        'fk_Recipe' => $id_Recipe
                    ]);
                } else {
                    if ($flagUnnecessary) {
                        $oldStep->delete();
                        $deletedCount++;
                    } elseif (!is_null($tempFilename)) {
                        $oldStep->update([
                            'Step_Description' => $temp,
                            'Step_number' => $i + 1 - $deletedCount,
                            'Image_address' => $tempFilename,
                        ]);
                    } else {
                        $oldStep->update([
                            'Step_Description' => $temp,
                            'Step_number' => $i + 1 - $deletedCount,
                            'Image_address' => $oldFileName,
                        ]);
                    }
                }
            }
        }
    }

    public function updateComment(Request $request, $id_Comment)
    {
        Comment::where('id_Comment', $id_Comment)->update([
            'Text' => $request->input('Text')
        ]);
        return redirect()->back()->with('success', 'Comment updated successfully');
    }

    public function deleteRecipe($recipe)
    {
        $id_Recipe = $recipe->id_Recipe;
        $mainComments = $recipe->Comment;
        $this->deleteRelatedComments($mainComments);
        Favorite_Recipe::where('fk_Recipe', $id_Recipe)->delete();
        Cooking_Instruction_Step::where('fk_Recipe', $id_Recipe)->delete();
        Recipe_Ingredient::where('fk_Recipe', $id_Recipe)->delete();
        Rating::where('fk_Recipe', $id_Recipe)->delete();
        Source::where('fk_Recipe', $id_Recipe)->delete();
        $recipe->delete();

        return redirect('/recipes');
    }

    public function deleteComment($id_Comment)
    {
        $comment = Comment::where('id_Comment', $id_Comment)->get();
        $this->deleteRelatedComments($comment);
        return redirect()->back()->with('success', 'Comment deleted successfully');
    }

    private function deleteRelatedComments($comments)
    {
        foreach ($comments as $reply) {
            $replies = $reply->Reply;
            if ($replies->count()) {
                $this->deleteRelatedComments($replies);
            }
            Comment::where('id_Comment', $reply->id_Comment)->delete();
        }
    }

    public function blockFavorite($id_Recipe)
    {
        $id = Auth::id();
        Favorite_Recipe::where('fk_User', $id)->where('fk_Recipe', $id_Recipe)->delete();
        return Redirect::back()->with('success', 'Successfully removed from favorites');
    }

    public function generateIngredients($id_Recipe, $calc)
    {
        $id_House = session('houseID');
        $recipe = Recipe::where('id_Recipe', $id_Recipe)->first();
        RecipeListController::abilityToMake($recipe, session('houseID'), $calc);
        if (count($recipe->missing) > 0) {
            foreach ($recipe->missing as $key => $ingredient) {
                $productStock = Product_Stock::where('fk_Product', $ingredient['id'])->get();
                if ($productStock->Count() > 1) {
                    foreach ($productStock as $pr) {
                        $stock = Stock::where([
                            'fk_Stock_card' => $pr->fk_Stock_card,
                            'fk_Home' => $id_House,
                        ])->first();
                        if ($stock) {
                            break;
                        }
                    }
                } elseif ($productStock->Count()) {
                    $stock = Stock::where([
                        'fk_Stock_card' => $productStock[0]->fk_Stock_card,
                        'fk_Home' => $id_House,
                    ])->first();
                } else {
                    $stock = null;
                }
                if ($stock) {
                    ResourceController::generatePurchaseOfferFromRecipe($id_House, $stock->fk_Warehouse_place,
                        $stock->fk_Stock_card, $ingredient['amount'],$ingredient['existingAmount']);
                } else {
                    $product = Product::where('id_Product', $ingredient['id'])->first();
                    $stockCard = Stock_Card::where([
                        'Name' => $product->Name,
                        'fk_Home' => $id_House,
                        'removed' => 0
                    ])->first();
                    if (!$stockCard) {
                        $stock_type = Stock_Type::where([
                            'Type_name' => 'food',
                            'fk_household_id' => $id_House
                        ])->first();
                        if (!$stock_type) {
                            $stock_type = Stock_Type::create([
                                'Type_name' => 'Food',
                                'fk_household_id' => $id_House
                            ]);
                        }
                        $stockCard = Stock_Card::create([
                            'Name' => $product->Name,
                            'fk_Home' => $id_House,
                            'fk_Stock_type' => $stock_type->id_Stock_type,
                            'measurement_unit' => $ingredient['unit'],
                            'removed' => 0,
                        ]);
                    }
                    $warehouse = Warehouse_place::where([
                        'fk_Home' => $id_House,
                        'Warehouse_name' => 'kitchen',
                        'removed' => 0,
                    ])->first();
                    if (!$warehouse) {
                        $warehouse = Warehouse_place::create([
                            'Warehouse_name' => 'Kitchen',
                            'fk_Home' => $id_House,
                            'removed' => 0,
                        ]);
                    }
                    ResourceController::generatePurchaseOfferFromRecipe($id_House, $warehouse->id_Warehouse_place,
                        $stockCard->id_Stock_card, $ingredient['amount'],$ingredient['existingAmount']);
                }
            }
            return back()->with('success', 'Purchase offer generated from missing recipe ingredients');
        } else {
            return back()->with('warning', 'You have everything you need');
        }
    }

    public function useComponents($id_Recipe, $calc)
    {
        $id_House = session('houseID');
        $recipe = Recipe::where('id_Recipe', $id_Recipe)->first();
        RecipeListController::abilityToMake($recipe, session('houseID'), $calc);
        $stockCard = Stock_Card::where([
            'Name' => $recipe->Name,
            'fk_Home' => $id_House,
            'removed' => 0
        ])->first();
        session(['recipeName' => $recipe]);
        if ($stockCard) {
            return $this->createStock($stockCard->id_Stock_card);
        } else {
            return ResourceListController::createStockCard($id_House);
        }
    }

    public static function createStock($stockCardID)
    {
        session(['actionType' => true]);
        return ResourceController::addResourceFromResourceView(session('houseID'), $stockCardID);
    }

    public static function useStock()
    {
        $recipe = session('recipeName');
        $houseID = session('houseID');
        foreach ($recipe->Recipe_Ingredient as $key => $ingredient) {
            $stock = Stock::where([
                'fk_Stock_card' => $recipe->forUse[$key]['stockCardID'],
                'fk_Home' => $houseID,
            ])->where('expiration_date', '>=', Carbon::now()->format('Y-m-d'));
            $all = $stock->get();
            $amount = round($recipe->forUse[$key]['amount'], 2);
            if ($all->Count() > 1) {
                $byWarehouse = $stock->groupby('fk_Warehouse_place')->selectRaw('*,SUM(quantity) as total_quantity')->get();
                $byBatch = Stock::where([
                    'fk_Stock_card' => $recipe->forUse[$key]['stockCardID'],
                    'fk_Home' => $houseID,
                ])->where('expiration_date', '>=', Carbon::now()->format('Y-m-d'))
                    ->groupby('fk_Batch')->selectRaw('*,SUM(quantity) as total_quantity')->get();
                $stockCardID = $recipe->forUse[$key]['stockCardID'];
                if ($byWarehouse->Count() > 1) {
                    if ($byBatch->Count() > 1) {
                        foreach ($byWarehouse as $item) {
                            $temp = Stock::where([
                                'fk_Stock_card' => $recipe->forUse[$key]['stockCardID'],
                                'fk_Home' => $houseID,
                            ])->where('expiration_date', '>=', Carbon::now()->format('Y-m-d'))
                                ->where('fk_Warehouse_place', $item->fk_Warehouse_place)->groupby('fk_Batch')
                                ->selectRaw('*,SUM(quantity) as total_quantity')->get();
                            $check = false;
                            foreach ($temp as $it) {
                                if ($it->total_quantity >= $amount) {
                                    ResourceController::saveStock($stockCardID, $it->fk_Warehouse_place,
                                        $it->fk_Batch, 0 - $amount, $it->expiration_date, 7, $houseID);
                                    $check = true;
                                    break;
                                } else {
                                    ResourceController::saveStock($stockCardID, $it->fk_Warehouse_place,
                                        $it->fk_Batch, 0 - $it->total_quantity, $it->expiration_date, 7, $houseID);
                                    $amount -= $it->total_quantity;
                                }
                            }
                            if ($check) {
                                break;
                            }
                        }
                    } else {
                        foreach ($byWarehouse as $item) {
                            if ($item->total_quantity >= $amount) {
                                ResourceController::saveStock($stockCardID, $item->fk_Warehouse_place,
                                    $item->fk_Batch, 0 - $amount, $item->expiration_date, 7, $houseID);
                                break;
                            } else {
                                ResourceController::saveStock($stockCardID, $item->fk_Warehouse_place,
                                    $item->fk_Batch, 0 - $item->total_quantity, $item->expiration_date, 7, $houseID);
                                $amount -= $item->total_quantity;
                            }
                        }
                    }
                } else {
                    if ($byBatch->Count() > 1) {
                        foreach ($byBatch as $item) {
                            if ($item->total_quantity >= $amount) {
                                ResourceController::saveStock($stockCardID, $item->fk_Warehouse_place,
                                    $item->fk_Batch, 0 - $amount, $item->expiration_date, 7, $houseID);
                                break;
                            } else {
                                ResourceController::saveStock($stockCardID, $item->fk_Warehouse_place,
                                    $item->fk_Batch, 0 - $item->total_quantity, $item->expiration_date, 7, $houseID);
                                $amount -= $item->total_quantity;
                            }
                        }
                    } else {
                        $st = $all->first();
                        ResourceController::saveStock($recipe->forUse[$key]['stockCardID'], $st->fk_Warehouse_place,
                            $st->fk_Batch, 0 - $amount, $st->expiration_date, 7, $houseID);
                    }
                }
            } else {
                $st = $all->first();
                ResourceController::saveStock($recipe->forUse[$key]['stockCardID'], $st->fk_Warehouse_place,
                    $st->fk_Batch, 0 - $amount, $st->expiration_date, 7, $houseID);
            }
        }
        session()->forget(['recipeName', 'actionType']);
        return redirect()->route('Recipe', ['id_Recipe' => $recipe->id_Recipe])
            ->with('success', 'Product added to the system, and required components consumed');
    }
}

