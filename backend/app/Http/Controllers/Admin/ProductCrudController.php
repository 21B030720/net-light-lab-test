<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProductRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ProductCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProductCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Product::class);
        CRUD::setRoute(config("backpack.base.route_prefix") . "/product");
        CRUD::setEntityNameStrings("product", "products");
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column("id")->label("ID");
        CRUD::column("category_id")
            ->type("select")
            ->entity("category")
            ->attribute("name")
            ->model("App\Models\Category");
        CRUD::column("name")->type("text");
        CRUD::column("slug")->type("text");
        CRUD::column("price")->type("number")->prefix('$')->decimals(2);
        CRUD::column("stock")->type("number");
        CRUD::column("image_url")->type("image");
        CRUD::column("is_active")->type("boolean");
        CRUD::column("created_at")->type("datetime");
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(ProductRequest::class);

        CRUD::field("category_id")
            ->type("select2")
            ->entity("category")
            ->attribute("name")
            ->model("App\Models\Category")
            ->label("Category");

        CRUD::field("name")->type("text");
        CRUD::field("slug")
            ->type("text")
            ->hint(
                "Will be automatically generated from the name if left empty"
            );
        CRUD::field("description")->type("textarea");
        CRUD::field("price")
            ->type("number")
            ->attributes(["step" => "0.01"]);
        CRUD::field("stock")->type("number");
        CRUD::field("image_url")
            ->type("upload")
            ->upload(true)
            ->disk("public")
            ->prefix("products");
        CRUD::field("is_active")->type("checkbox");
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
