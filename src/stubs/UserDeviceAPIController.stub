<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\UserDeviceRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

/**
 * Class UserDeviceAPIController
 */
class UserDeviceAPIController extends Controller
{
    /** @var UserDeviceRepository */
    public $userDeviceRepo;

    /**
     * UserDeviceAPIController constructor.
     * @param  UserDeviceRepository  $userDeviceRepo
     */
    public function __construct(UserDeviceRepository $userDeviceRepo)
    {
        $this->userDeviceRepo = $userDeviceRepo;
    }

    /**
     * @param  Request  $request
     *
     * @return JsonResponse
     */
    public function registerDevice(Request $request)
    {
        $this->userDeviceRepo->updateOrCreate($request->all());

        return $this->sendSuccess('The device has been registered successfully.');
    }

    /**
     * @param $playerId
     *
     * @return JsonResponse
     */
    public function updateNotificationStatus($playerId)
    {
        $this->userDeviceRepo->updateStatus($playerId);

        return $this->sendSuccess('The notification status has been updated successfully.');
    }

    /**
     * @param $message
     *
     * @return JsonResponse
     */
    private function sendSuccess($message)
    {
        return Response::json([
            'success' => true,
            'message' => $message
        ], 200);
    }
}
