<?php

namespace Adminetic\Website\Repository;

use Illuminate\Support\Facades\Cache;
use Intervention\Image\Facades\Image;
use Adminetic\Website\Models\Admin\Service;
use Adminetic\Website\Http\Requests\ServiceRequest;
use Adminetic\Website\Contracts\ServiceRepositoryInterface;

class ServiceRepository implements ServiceRepositoryInterface
{
    // Service Index
    public function indexService()
    {
        $services = config('coderz.caching', true)
            ? (Cache::has('services') ? Cache::get('services') : Cache::rememberForever('services', function () {
                return Service::orderBy('position')->get();
            }))
            : Service::orderBy('position')->get();
        return compact('services');
    }

    // Service Create
    public function createService()
    {
        //
    }

    // Service Store
    public function storeService(ServiceRequest $request)
    {
        $service = Service::create($request->validated());
        $this->uploadImage($service);
        if (request()->category_id) {
            $service->categories()->attach(request()->category_id);
        }
    }

    // Service Show
    public function showService(Service $service)
    {
        return compact('service');
    }

    // Service Edit
    public function editService(Service $service)
    {
        return compact('service');
    }

    // Service Update
    public function updateService(ServiceRequest $request, Service $service)
    {
        $service->update($request->validated());
        $this->uploadImage($service);
        if (request()->category_id) {
            $service->categories()->sync(request()->category_id);
        }
    }

    // Service Destroy
    public function destroyService(Service $service)
    {
        $service->hardDelete('image');
        $service->delete();
    }

    // Upload Image
    protected function uploadImage(Service $service)
    {
        if (request()->icon_image) {
            $service->update([
                'icon_image' => request()->icon_image->store('website/service/image', 'public')
            ]);
            $image = Image::make(request()->file('icon_image')->getRealPath());
            $image->save(public_path('storage/' . $service->icon_image));
        }

        if (request()->image) {
            $thumbnails = [
                'storage' => 'website/service/icon',
                'width' => '512',
                'height' => '512',
                'quality' => '80',
                'thumbnails' => [
                    [
                        'thumbnail-name' => 'small',
                        'thumbnail-width' => '150',
                        'thumbnail-height' => '100',
                        'thumbnail-quality' => '50'
                    ]
                ]
            ];
            $service->makeThumbnail('image', $thumbnails);
        }
    }
}