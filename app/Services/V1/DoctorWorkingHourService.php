<?php

namespace App\Services\V1;

use App\Models\Employee;
use App\Models\DoctorWorkingHour;
use App\Http\Resources\V1\DoctorWorkingHourResource;
use App\Repositories\V1\DoctorWorkingHourRepository;

class DoctorWorkingHourService
{
    protected $repo;

    public function __construct(DoctorWorkingHourRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index(Employee $doctor)
    {
        $workingHours = $this->repo->all($doctor);
        $workingHours = DoctorWorkingHourResource::collection($workingHours);
        $message = __('messages.index_success', ['class' => __('working hours')]);
        $code = 200;
        return ['data' => $workingHours, 'message' => $message, 'code' => $code];
    }

    public function store($request)
    {
        $workingHours = $this->repo->create($request->validated());
        $workingHours = new DoctorWorkingHourResource($workingHours);
        $message = __('messages.store_success', ['class' => __('working hours')]);
        $code = 201;
        return ['data' =>  $workingHours, 'message' => $message, 'code' => $code];
    }

    public function update($request, DoctorWorkingHour $workingHour)
    {
        $workingHours = $this->repo->update($request->validated(), $workingHour);
        $workingHours = new DoctorWorkingHourResource($workingHours);

        $message = __('messages.update_success', ['class' => __('working hours')]);
        $code = 200;
        return ['data' => $workingHours, 'message' => $message, 'code' => $code];
    }

    public function destroy(DoctorWorkingHour $workingHour)
    {
        $this->repo->delete($workingHour);

        $message = __('messages.destroy_success', ['class' => __('working hours')]);
        $code = 200;
        return ['data' => [], 'message' => $message, 'code' => $code];
    }
}
