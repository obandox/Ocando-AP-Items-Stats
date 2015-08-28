<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;

use Response;
use App\Item;

class ItemsController extends Controller
{
	 public function index($region, $version, $queue){
	 	$items = Item::all();

	 	return Response::json($items);
	 }

	 public function listItems($region, $version, $queue, $type = false){
	 	$queue = strtoupper($queue);
	 	return Response::json(Item::listInMatchs($region,"AP_ITEM_DATASET/$version/$queue",$type));
	 }

}