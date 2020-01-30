<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
use \Illuminate\Http\Response;

class ApiController extends Controller {

	/**
     * @var int
     */
    protected $statusCode = Response::HTTP_OK;

    /**
     * @var App\Repository\BaseRepository
     */
    protected $repository;

    /**
     * @var App\Models
     */
    protected $model;

	/**
     * @return int
     */
	public function getStatusCode()
    {
        return $this->statusCode;
    }

	/**
     * @param int $statusCode
     */
    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

	/**
	 * Return Json
     *
	 * @param array $data
	 * @param array $headers
	 * @return mixed
	*/
	public function respond($data, array $headers = [])
	{
		return response()->json($data, $this->getStatusCode(), $headers);
	}

	/**
	 * Return json with code 200
     *
	 * @param array $data
	 * @param array $headers
	 */
	public function respondSuccess($data = [], array $headers = [])
	{
        return $this->respond($data, $headers);

    }

	/**
	 * Return json with code 201
     *
	 * @param array $data
	 * @param array $headers
	 */
	public function respondCreated($data = [], array $headers = [])
	{
		$this->setStatusCode(Response::HTTP_CREATED);

        return $this->respond($data, $headers);

    }

	/**
	 * Return Json with code 404
     *
	 * @param string $message
	 * @param array  $headers
	 */
	public function respondNotFound(string $message = 'Not Found!', array $headers = [])
	{
		$this->setStatusCode(Response::HTTP_NOT_FOUND);

		return $this->respond([

            'status' => 'Not found',
            'message' => $message,

        ], $headers);

    }
	/**
	 * Return json with code 500
     *
	 * @param string $message
	 * @param array  $headers
	 */
	public function respondInternalError(string $message, array $headers = [])
	{
		$this->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);

        return $this->respond([

            'status' => 'Internal server error',
            'message' => $message,

        ], $headers);

    }

	/**
	 * Return Json with code 422
     *
	 * @param string $message
     * @param $errors
	 * @param array  $headers
	 */
	public function respondValidationError(string $message, $errors, array $headers = [])
	{
		$this->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY);

        return $this->respond([

            'status' => 'Unprocessable Entity',
            'message' => $message,
            'data' => $errors

        ], $headers);

    }

	/**
	 * Return json with code 400
     *
	 * @param string $message
	 * @param array  $headers
	 */
	public function respondWithError($message, array $headers = [])
	{
		$this->setStatusCode(Response::HTTP_BAD_REQUEST);

        return $this->respond([

            'status' => 'Bad request',
            'message' => $message,

        ], $headers);
	}

	/**
     * @param Paginator $paginate
     * @param $data
	 * @param array $headers
     * @return mixed
     */
	public function respondWithPagination(Paginator $paginate, string $message, $data, array $headers = [])
	{
        $data = array_merge($data, [
            'paginator' => [
                'total_count'  => $paginate->total(),
                'total_pages' => ceil($paginate->total() / $paginate->perPage()),
                'current_page' => $paginate->currentPage(),
                'limit' => $paginate->perPage(),
            ]
        ]);

        return $this->respondCreated($message, $data, $headers);
    }
}
