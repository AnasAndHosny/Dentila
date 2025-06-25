<?php

namespace App\Services\V1;

use App\Http\Resources\V1\DiseaseResource;
use App\Models\Disease;
use App\Repositories\V1\DiseaseRepository;

class DiseaseService
{
    protected $diseaseRepository;

    public function __construct(DiseaseRepository $diseaseRepository)
    {
        $this->diseaseRepository = $diseaseRepository;
    }

    public function index(): array
    {
        $diseases = $this->diseaseRepository->all();
        $diseases = DiseaseResource::collection($diseases);
        $message = __('messages.index_success', ['class' => __('diseases')]);
        $code = 200;
        return ['data' => $diseases, 'message' => $message, 'code' => $code];
    }

    public function store($request): array
    {
        $this->diseaseRepository->create($request);

        $diseases = $this->diseaseRepository->all();
        $diseases = DiseaseResource::collection($diseases);

        $message = __('messages.store_success', ['class' => __('disease')]);
        $code = 201;
        return ['data' =>  $diseases, 'message' => $message, 'code' => $code];
    }

    public function update($request, Disease $disease): array
    {
        $this->diseaseRepository->update($request, $disease);

        $diseases = $this->diseaseRepository->all();
        $diseases = DiseaseResource::collection($diseases);

        $message = __('messages.update_success', ['class' => __('disease')]);
        $code = 200;
        return ['data' => $diseases, 'message' => $message, 'code' => $code];
    }

    public function destroy(Disease $disease): array
    {
        $this->diseaseRepository->delete($disease);

        $diseases = $this->diseaseRepository->all();
        $diseases = DiseaseResource::collection($diseases);

        $message = __('messages.destroy_success', ['class' => __('disease')]);
        $code = 200;
        return ['data' => $diseases, 'message' => $message, 'code' => $code];
    }
}
