<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceType;

class ServicesPageController extends Controller
{
    public function index()
    {
        $services = Service::query()
            ->where('is_active', true)
            ->with('images')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get();

        $serviceTypes = ServiceType::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['key', 'name', 'schema']);

        $serviceTypeSchemas = $serviceTypes->mapWithKeys(function ($t) {
            return [$t->key => ($t->schema ?: null)];
        })->toArray();

        $serviceTypeNames = $serviceTypes->mapWithKeys(function ($t) {
            return [$t->key => $t->name];
        })->toArray();

        return view('pages.services', compact('services', 'serviceTypeSchemas', 'serviceTypeNames'));
    }

    public function show(Service $service)
    {
        if (!$service->is_active) {
            abort(404);
        }

        $service->load('images');

        $rawType = (string) ($service->service_type ?: 'other');
        $serviceTypeKey = $rawType !== '' ? $rawType : 'other';

        $serviceType = ServiceType::query()
            ->where('key', $serviceTypeKey)
            ->where('is_active', true)
            ->first();

        if (!$serviceType) {
            $normalized = strtolower(trim($serviceTypeKey));
            $normalized = preg_replace('/[^a-z0-9]+/', '_', $normalized);
            $normalized = trim((string) $normalized, '_');

            if ($normalized !== '' && $normalized !== $serviceTypeKey) {
                $serviceType = ServiceType::query()
                    ->where('key', $normalized)
                    ->where('is_active', true)
                    ->first();
                if ($serviceType) {
                    $serviceTypeKey = $serviceType->key;
                }
            }
        }

        if (!$serviceType && $serviceTypeKey !== '') {
            $nameNormalized = strtolower(trim($serviceTypeKey));
            $serviceType = ServiceType::query()
                ->whereRaw('LOWER(name) = ?', [$nameNormalized])
                ->where('is_active', true)
                ->first();
            if ($serviceType) {
                $serviceTypeKey = $serviceType->key;
            }
        }

        if (!$serviceType) {
            $serviceTypeKey = 'other';
            $serviceType = ServiceType::query()
                ->where('key', 'other')
                ->where('is_active', true)
                ->first();
        }

        $serviceTypeName = $serviceType?->name ?: $serviceTypeKey;
        $serviceTypeSchema = is_array($serviceType?->schema ?? null) ? $serviceType->schema : [];

        return view('pages.service_show', compact('service', 'serviceTypeKey', 'serviceTypeName', 'serviceTypeSchema'));
    }
}
