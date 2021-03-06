<?php namespace App\Http\Controllers\API;

use App\Http\Requests;
use App\Libraries\Repositories\PostRepository;
use App\Models\Post;
use App\Models\IwomenPost;
use App\Models\Resources;
use App\Models\SubResourceDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\AppBaseController as AppBaseController;
use Response;
use Validator;
use App\Models\Gcm;
use PushNotification;

class PostAPIController extends AppBaseController
{
	/** @var  PostRepository */
	private $postRepository;

	function __construct(PostRepository $postRepo)
	{
		$this->postRepository = $postRepo;
	}

	public function getPostCount($user_id){
		$count = Post::where('userId', $user_id)->count();
		return response()->json($count);
	}

	/**
	 * Display a listing of the Post.
	 * GET|HEAD /posts
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{
		$offset  = $request->page ? $request->page : 1;
		$limit   = $request->limit ? $request->limit : 12;
		$category = $request->category;
		$isAllow = $request->isAllow ? $request->isAllow : '';
		$userId = $request->userId ? $request->userId : 0;
		
		$offset  = ($offset - 1) * $limit;
		if($category){
			if($isAllow && $isAllow != ''){
				$posts = Post::where('isAllow',$isAllow)->where('category_id', $category)->orderBy('id','desc')->offset($offset)->limit($limit)->get();
			}else{
				$posts = Post::where('category_id', $category)->orderBy('id','desc')->offset($offset)->limit($limit)->get();
			}
		}elseif($userId > 0){
			if($isAllow && $isAllow != ''){
				$posts = Post::where('isAllow',$isAllow)->where('userId', $userId)->orderBy('created_at','desc')->offset($offset)->limit($limit)->get();
			}else{
				$posts = Post::where('userId', $userId)->orderBy('created_at','desc')->offset($offset)->limit($limit)->get();
			}
		}else{
			if($isAllow && $isAllow != ''){
				$posts = Post::where('isAllow',$isAllow)->orderBy('created_at','desc')->offset($offset)->limit($limit)->get();
			}else{
				$posts = Post::orderBy('created_at','desc')->offset($offset)->limit($limit)->get();
			}
		}
		
		return response()->json($posts);
	}

	public function search(Request $request){
		$search  = $request->keywords ? $request->keywords : '';
		$offset  = $request->page ? $request->page : 1;
		$limit   = $request->limit ? $request->limit : 12;
		$isAllow = $request->isAllow ? $request->isAllow : 1;
		$category = $request->category;
		
		$offset  = ($offset - 1) * $limit;

		$posts = Post::where('title','like', '%'.$search.'%')
								->orwhere('titleMm','like', '%'.$search.'%')
								->orwhere('content','like', '%'.$search.'%')
								->orwhere('content_mm','like', '%'.$search.'%')
								->orwhere('postUploadName','like', '%'.$search.'%')
								->where('isAllow',$isAllow)
								->where('category_id', $category)
								->orderBy('postUploadedDate','desc')
								->offset($offset)->limit($limit)->get();
		return response()->json($posts);
	}

	public function share($id, Request $request)
	{
		$post_type = $request->input('postType');
		switch ($post_type) {
			case 'Post':
				$post = Post::find($id);
				if($post){
					$post->share_count = $post->share_count + 1;
					$post->update();
					return response()->json($post->share_count);
				}
				break;

			case 'iWomenPost':
				$post = IwomenPost::find($id);
				if($post){
					$post->share_count = $post->share_count + 1;
					$post->update();
					return response()->json($post->share_count);
				}
				break;

			case 'Resources':
				$post = Resources::find($id);
				if($post){
					$post->share_count = $post->share_count + 1;
					$post->update();
					return response()->json($post->share_count);
				}
				break;

			case 'SubResourceDetail':
				$post = SubResourceDetail::find($id);
				if($post){
					$post->share_count = $post->share_count + 1;
					$post->update();
					return response()->json($post->share_count);
				}
				break;
			
			default:
				# code...
				break;
		}
		return response()->json("Successfully shared your post!");
	}

	/**
	 * Show the form for creating a new Post.
	 * GET|HEAD /posts/create
	 *
	 * @return Response
	 */
	public function create()
	{
	}

	/**
	 * Store a newly created Post in storage.
	 * POST /posts
	 *
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function store(Request $request)
	{
		if(sizeof(Post::$rules) > 0)
			$this->validateRequestOrFail($request, Post::$rules);

		$input = $request->all();
		$message['message'] = isset($input['content']) && $input['content'] ? $input['content']: $input['content_mm'];
		$input['objectId'] = 'Post'.str_random(10);
		$input['postUploadNameMM'] = $input["postUploadName"];
		$input['content'] = isset($input['content']) && $input['content']? $input['content'] : 'This content is currently only available in Myanmar language.';
		$input['content_mm'] = isset($input['content_mm']) && $input['content_mm'] ? $input['content_mm'] : 'အဂၤလိပ္ ဘာသာစကားၿဖင့္သာ လက္ရွိတြင္ ေပးပိုု ့ထားပါသည္။';

		$posts = $this->postRepository->create($input);

		if($posts->category_id == 'MqLh2pZShc' || $posts->category_id == 'DuXAXGFbEe'){
			$device_list = [];
			$gcm = Gcm::all();
			foreach ($gcm as $key => $value) {
				$device_list[] = PushNotification::Device($value->reg_id);
	  		}
	  		if($posts->category_id == 'MqLh2pZShc'){
	  			$message['title'] = isset($input['content']) ? 'Market' : 'အေရာင္း / အ၀ယ္';
	  		}else{
	  			$message['title'] = isset($input['content']) ? 'Q&A' : 'အေမးအေျဖ';
	  		}
	  		
	  		$message['postId'] = $posts->id;
			$devices = PushNotification::DeviceCollection($device_list);
			$message = PushNotification::Message(json_encode($message),array());

			$collection = PushNotification::app('appNameAndroid')
			    ->to($devices)
			    ->send($message);
		}

		return $this->sendResponse($posts->toArray(), "Post saved successfully");
	}

	public function postUploadImage(Request $request){
		
        $photoname = $this->uploadImage($request->file('image'), '/posts_photo/');
        
        return response()->json($photoname);
       
    }

    public function postUploadAudio(Request $request){

        $audio_name = $this->uploadAudio($request->uploaded_file, '/posts_audio/');
        
        return response()->json($audio_name);
    }

    public function postUploadVideo(Request $request){
        
        $video_name = $this->uploadAudio($request->uploaded_file, '/posts_video/');
        
        return response()->json($video_name);
    }

	/**
	 * Display the specified Post.
	 * GET|HEAD /posts/{id}
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id, Request $request)
	{
		if($request->isAllow != null)
			$post = Post::where('id', $id)->where('isAllow', $request->isAllow)->first();
		else
			$post = Post::where('id', $id)->first();
		return response()->json($post);
	}

	/**
	 * Show the form for editing the specified Post.
	 * GET|HEAD /posts/{id}/edit
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		// maybe, you can return a template for JS
//		Errors::throwHttpExceptionWithCode(Errors::EDITION_FORM_NOT_EXISTS, ['id' => $id], static::getHATEOAS(['%id' => $id]));
	}

	/**
	 * Update the specified Post in storage.
	 * PUT/PATCH /posts/{id}
	 *
	 * @param  int              $id
	 * @param Request $request
	 *
	 * @return Response
	 */
	public function update($id, Request $request)
	{
		$input = $request->all();

		/** @var Post $post */
		$post = $this->postRepository->apiFindOrFail($id);

		$result = $this->postRepository->updateRich($input, $id);

		$post = $post->fresh();

		return $this->sendResponse($post->toArray(), "Post updated successfully");
	}

	/**
	 * Remove the specified Post from storage.
	 * DELETE /posts/{id}
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$this->postRepository->apiDeleteOrFail($id);

		return $this->sendResponse($id, "Post deleted successfully");
	}
}
