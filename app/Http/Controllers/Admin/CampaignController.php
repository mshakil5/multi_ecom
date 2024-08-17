<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Campaign;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CampaignController extends Controller
{
    public function getCampaigns()
    {
        $data = Campaign::orderby('id','DESC')->get();
        return view('admin.campaign.index', compact('data'));
    }

    public function campaignStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'short_description' => 'nullable|string',
            'banner_image' => 'required|image|max:2048',
            'small_image' => 'required|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $campaign = new Campaign();
        $campaign->title = $request->title;
        $campaign->slug = Str::slug($request->title);
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->short_description = $request->short_description;
        $campaign->created_by = auth()->user()->id;

        if ($request->hasFile('banner_image')) {
            $image = $request->file('banner_image');
            $filename = mt_rand(10000000, 99999999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/campaign_banner'), $filename);
            $campaign->banner_image = $filename;
        }

        if ($request->hasFile('small_image')) {
            $image = $request->file('small_image');
            $filename = mt_rand(10000000, 99999999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/campaign_small'), $filename);
            $campaign->small_image = $filename;
        }

        $campaign->save();

        return response()->json(['message' => 'Campaign created successfully', 'data' => $campaign]);
    }

    public function campaignEdit($id)
    {
        $info = Campaign::where('id', $id)->first();
        return response()->json($info);
    }

    public function campaignUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'start_date' => 'required',
            'end_date' => 'required',
            'short_description' => 'nullable|string',
            'banner_image' => 'nullable|image|max:2048',
            'small_image' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $campaign = Campaign::find($request->codeid);
        $campaign->title = $request->title;
        $campaign->slug = Str::slug($request->title);
        $campaign->start_date = $request->start_date;
        $campaign->end_date = $request->end_date;
        $campaign->short_description = $request->short_description;
        $campaign->created_by = auth()->user()->id;

        if ($request->hasFile('banner_image')) {

            if ($campaign->banner_image && file_exists(public_path('images/campaign_banner/' . $campaign->banner_image))) {
                unlink(public_path('images/campaign_banner/' . $campaign->banner_image));
            }

            $image = $request->file('banner_image');
            $filename = mt_rand(10000000, 99999999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/campaign_banner'), $filename);
            $campaign->banner_image = $filename;
        }

        if ($request->hasFile('small_image')) {

            if ($campaign->small_image && file_exists(public_path('images/campaign_small/' . $campaign->small_image))) {
                unlink(public_path('images/campaign_small/' . $campaign->small_image));
            }

            $image = $request->file('small_image');
            $filename = mt_rand(10000000, 99999999) . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/campaign_small'), $filename);
            $campaign->small_image = $filename;
        }

        $campaign->save();

        return response()->json(['message' => 'Campaign created successfully', 'data' => $campaign]);
    }

    public function campaignDelete($id)
    {
        $campaign = Campaign::find($id);

        if ($campaign->banner_image && file_exists(public_path('images/campaign_banner/' . $campaign->banner_image))) {
            unlink(public_path('images/campaign_banner/' . $campaign->banner_image));
        }
        if ($campaign->small_image && file_exists(public_path('images/campaign_small/' . $campaign->small_image))) {
            unlink(public_path('images/campaign_small/' . $campaign->small_image));
        }
        $campaign->delete();
        return response()->json(['message' => 'Campaign deleted successfully']);
    }

}
