<?php

namespace App\Http\Controllers\BackEnd\PaymentGateway;

use App\Http\Controllers\Controller;
use App\Models\PaymentGateway\OfflineGateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Mews\Purifier\Facades\Purifier;

class OfflineGatewayController extends Controller
{
  public function index()
  {
    $offlineGateways = OfflineGateway::orderBy('id', 'desc')->get();

    return view('backend.payment_gateways.offline_gateways.index', compact('offlineGateways'));
  }

  public function store(Request $request)
  {
    $in = $request->all();
    $rules = [
      'name' => 'required',
      'attachment_status' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($in, $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    $in['instructions'] = Purifier::clean($request->add_instructions, 'youtube');

    OfflineGateway::create($in);

    session()->flash('success', 'New offline payment gateway added successfully!');

    return 'success';
  }

  public function updateRoomBookingStatus(Request $request)
  {
    $offlineGateway = OfflineGateway::where('id', $request->offline_gateway_id)->first();

    if ($request->status == 1) {
      $offlineGateway->update(['status' => 1]);
    } else {
      $offlineGateway->update(['status' => 0]);
    }

    session()->flash('success', 'Room booking status updated successfully!');

    return redirect()->back();
  }

  public function update(Request $request)
  {
    $rules = [
      'name' => 'required',
      'attachment_status' => 'required',
      'serial_number' => 'required'
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
      return Response::json([
        'errors' => $validator->getMessageBag()->toArray()
      ], 400);
    }

    OfflineGateway::where('id', $request->offline_gateway_id)->first()->update($request->except('instructions') + [
      'instructions' => Purifier::clean($request->instructions, 'youtube')
    ]);

    session()->flash('success', 'Offline payment gateway updated successfully!');

    return 'success';
  }

  public function delete(Request $request)
  {
    OfflineGateway::where('id', $request->offline_gateway_id)->first()->delete();

    session()->flash('success', 'Offline payment gateway deleted successfully!');

    return redirect()->back();
  }
}
