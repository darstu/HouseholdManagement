<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;

class AddRecipe extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Name' => 'required|max:255',
            'Description' => 'required',
            'DishType' => 'required|max:255',
            'DietType' => 'required|max:255',
            'Difficulty' => 'required',
            'Cooking_time' => 'required',
            'Servings_Count' => 'required|numeric',
            'inputIngredientName' => 'check_array|max:255',
            'inputIngredientAmount' => 'check_array',
            'inputIngredientAmount.*' =>'nullable|numeric',
            'inputInstructionName'=>'check_array',
            'Image'=>'bail|required_without_all:imageSource,oldPhoto|image|mimes:jpeg,png,jpg,svg',
        ];
    }

    public function messages()
    {
        return [
            'Name.required'=>'Recipe title is required',

            'Description.required' => 'Recipe description is required',

            'Difficulty.required' =>'Recipe difficulty is required',

            'Cooking_time.required' => 'Recipe cooking time is required',

            'Servings_Count.required'=>'Recipe servings count is required',
            'Servings_Count.numeric'=>'Recipe servings count has to be a number',

            'DishType.required' => 'Dish type is required',
            'DietType.required' => 'Diet type is required',

            'inputIngredientName.check_array' => 'At least one ingredient name is required',

            'inputIngredientAmount.check_array' => 'At least one ingredient amount is required',
            'inputIngredientAmount.*.numeric' =>'Ingredient amount has to be a number',

            'inputInstructionName.check_array' => 'At least one cooking instruction step is required',
            'Image.required_without_all' =>'Recipe image is required',
            'Image.image' =>'The file has to be an image',
            'Image.mimes' =>'Suitable files: :values',

        ];
    }
}
