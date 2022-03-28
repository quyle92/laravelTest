<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class ChannelController extends Controller
{
   public function index()
   {
   		return view('channel.index');
   }

   public function getAllPic()
   {
   		 $video = \App\Video::find(1);
   		 dd($video);
   }
}
