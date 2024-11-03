<?php

namespace App\Http\Api\Controllers;

use App\Http\Requests\UpdateItemRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Item;

class ItemController extends Controller
{
    public function update(UpdateItemRequest $request, $id)
    {
        $item = Item::findOrFail($id);
        $validData = $request->validated();
        $possible_updates = ["category", "quantity"];
        foreach ($possible_updates as $field) {
            if (array_key_exists($field, $validData)) {
                $item->{$field} = $validData[$field];
            }
        }
        $item->save();
        return response()->json($item);
    }
}
