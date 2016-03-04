<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Requests\CreateResourcesRequest;
use App\Http\Requests\UpdateResourcesRequest;
use App\Libraries\Repositories\ResourcesRepository;
use Flash;
use Mitul\Controller\AppBaseController as AppBaseController;
use Response;

class ResourcesController extends AppBaseController
{

	/** @var  ResourcesRepository */
	private $resourcesRepository;

	function __construct(ResourcesRepository $resourcesRepo)
	{
		$this->resourcesRepository = $resourcesRepo;
	}

	/**
	 * Display a listing of the Resources.
	 *
	 * @return Response
	 */
	public function index()
	{
		$resources = $this->resourcesRepository->paginate(10);

		return view('resources.index')
			->with('resources', $resources);
	}

	/**
	 * Show the form for creating a new Resources.
	 *
	 * @return Response
	 */
	public function create()
	{
		return view('resources.create');
	}

	/**
	 * Store a newly created Resources in storage.
	 *
	 * @param CreateResourcesRequest $request
	 *
	 * @return Response
	 */
	public function store(CreateResourcesRequest $request)
	{
		$input = $request->all();

		$input['objectId'] = str_random(10);

		$resources = $this->resourcesRepository->create($input);

		Flash::success('Resources saved successfully.');

		return redirect(route('resources.index'));
	}

	/**
	 * Display the specified Resources.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function show($id)
	{
		$resources = $this->resourcesRepository->find($id);

		if(empty($resources))
		{
			Flash::error('Resources not found');

			return redirect(route('resources.index'));
		}

		return view('resources.show')->with('resources', $resources);
	}

	/**
	 * Show the form for editing the specified Resources.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function edit($id)
	{
		$resources = $this->resourcesRepository->find($id);

		if(empty($resources))
		{
			Flash::error('Resources not found');

			return redirect(route('resources.index'));
		}

		return view('resources.edit')->with('resources', $resources);
	}

	/**
	 * Update the specified Resources in storage.
	 *
	 * @param  int              $id
	 * @param UpdateResourcesRequest $request
	 *
	 * @return Response
	 */
	public function update($id, UpdateResourcesRequest $request)
	{
		$resources = $this->resourcesRepository->find($id);

		if(empty($resources))
		{
			Flash::error('Resources not found');

			return redirect(route('resources.index'));
		}

		$this->resourcesRepository->updateRich($request->all(), $id);

		Flash::success('Resources updated successfully.');

		return redirect(route('resources.index'));
	}

	/**
	 * Remove the specified Resources from storage.
	 *
	 * @param  int $id
	 *
	 * @return Response
	 */
	public function destroy($id)
	{
		$resources = $this->resourcesRepository->find($id);

		if(empty($resources))
		{
			Flash::error('Resources not found');

			return redirect(route('resources.index'));
		}

		$this->resourcesRepository->delete($id);

		Flash::success('Resources deleted successfully.');

		return redirect(route('resources.index'));
	}
}
