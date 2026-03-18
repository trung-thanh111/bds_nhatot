<?php

namespace App\Services\V2\Impl\RealEstate;

use App\Repositories\RealEstate\AgentRepo;
use App\Repositories\RealEstate\PropertyRepo;
use App\Repositories\RealEstate\ContactRequestRepo;
use App\Repositories\RealEstate\FloorplanRepo;
use App\Repositories\RealEstate\ProjectRepository;
use App\Repositories\Post\PostRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RealEstateStatisticService
{
    protected $agentRepo;
    protected $propertyRepo;
    protected $contactRequestRepo;
    protected $floorplanRepo;
    protected $projectRepository;
    protected $postRepository;

    public function __construct(
        AgentRepo $agentRepo,
        PropertyRepo $propertyRepo,
        ContactRequestRepo $contactRequestRepo,
        FloorplanRepo $floorplanRepo,
        ProjectRepository $projectRepository,
        PostRepository $postRepository
    ) {
        $this->agentRepo = $agentRepo;
        $this->propertyRepo = $propertyRepo;
        $this->contactRequestRepo = $contactRequestRepo;
        $this->floorplanRepo = $floorplanRepo;
        $this->projectRepository = $projectRepository;
        $this->postRepository = $postRepository;
    }

    public function getStats()
    {
        $now = Carbon::now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Counts
        $agentCount = $this->agentRepo->all()->count();
        $propertyCount = $this->propertyRepo->all()->count();
        $contactRequestCount = $this->contactRequestRepo->all()->count();
        $floorplanCount = $this->floorplanRepo->all()->count();
        $projectCount = $this->projectRepository->all()->count();
        $postCount = $this->postRepository->all()->count();

        // Growth for Contact Requests
        $currentMonthCR = $this->contactRequestRepo->findByCondition([
            ['created_at', '>=', $startOfMonth]
        ], true)->count();

        $lastMonthCR = $this->contactRequestRepo->findByCondition([
            ['created_at', '>=', $startOfLastMonth],
            ['created_at', '<=', $endOfLastMonth]
        ], true)->count();

        $growth = 0;
        if ($lastMonthCR > 0) {
            $growth = (($currentMonthCR - $lastMonthCR) / $lastMonthCR) * 100;
        } elseif ($currentMonthCR > 0) {
            $growth = 100;
        }

        return [
            'agentCount' => $agentCount,
            'propertyCount' => $propertyCount,
            'contactRequestCount' => $contactRequestCount,
            'floorplanCount' => $floorplanCount,
            'projectCount' => $projectCount,
            'postCount' => $postCount,
            'currentMonthCR' => $currentMonthCR,
            'lastMonthCR' => $lastMonthCR,
            'growth' => round($growth, 2),
            'crChart' => $this->getCRChartData()
        ];
    }

    public function getCRChartData($type = 1)
    {
        $labels = [];
        $datasets = [];

        if ($type == 1) { // Annual (by month)
            for ($i = 1; $i <= 12; $i++) {
                $labels[] = "Tháng $i";
                $datasets[] = $this->contactRequestRepo->findByCondition([
                    [DB::raw('MONTH(created_at)'), '=', $i],
                    [DB::raw('YEAR(created_at)'), '=', date('Y')]
                ], true)->count();
            }
        }

        return [
            'label' => $labels,
            'data' => $datasets
        ];
    }

    public function getRecentContactRequests($limit = 10)
    {
        return $this->contactRequestRepo->findByCondition([], true, ['project'], ['id', 'DESC'])->take($limit);
    }
}
