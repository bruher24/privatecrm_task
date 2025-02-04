<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Ration;
use App\Models\Tariff;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use DateTime;

class OrderController extends Controller
{
    /**
     * @param Request $request
     * @return void
     */
    public function createOrder(Request $request)
    {
        $valid = $request->validate([
            'name' => 'string|min:3|max:30|not_regex:/\d+/|required',
            'phone' => 'digits:11|unique:orders,client_phone|required',
            'tariff' => 'in:1,2|required',
            'schedule' => 'in:every_day,every_other_day,every_other_day_twice|required',
            'comment' => 'string|nullable',
            'first_date' => 'array|required',
            'last_date' => 'array|required',
            'first_date.*' => 'date|required',
            'last_date.*' => 'date|required',
        ]);

        $input = $request->all();

        $order = new Order();
        $order->client_name = $input['name'];
        $order->client_phone = $input['phone'];
        $order->tariff_id = $input['tariff'];
        $order->schedule_type = $input['schedule'];
        $order->comment = $input['comment'];
        $order->first_date = min($input['first_date']);
        $order->last_date = max($input['last_date']);
        $order->save();

        $cookBefore = DB::table('tariffs')->where('id', '=', $order->tariff_id)->value('cooking_day_before');
        $this->createRations($order->schedule_type, $input['first_date'], $input['last_date'], $order->id, $cookBefore);

        $order->first_date = DB::table('rations')->where('order_id', '=', $order->id)->min('delivery_date');
        $order->last_date = DB::table('rations')->where('order_id', '=', $order->id)->max('delivery_date');
        $order->save();
    }

    /**
     * @param string $scheduleType
     * @param array $firstDatesArr
     * @param array $lastDatesArr
     * @param int $orderId
     * @param int $cookBefore
     * @return void
     */
    private function createRations(string $scheduleType, array $firstDatesArr, array $lastDatesArr, int $orderId, int $cookBefore)
    {
        switch ($scheduleType) {
            case 'every_day':
                $daysShift = '1 day';
                $rationsCol = 1;
                break;
            case 'every_other_day':
                $daysShift = '2 days';
                $rationsCol = 1;
                break;
            case 'every_other_day_twice':
                $daysShift = '2 days';
                $rationsCol = 2;
                break;
        }

        foreach ($firstDatesArr as $index => $date) {
            $interval = CarbonPeriod::create($date, $daysShift, $lastDatesArr[$index]);
            foreach ($interval as $day) {
                $ration = Ration::create([
                    'order_id' => $orderId,
                    'delivery_date' => $day->format('Y-m-d'),
                    'cooking_date' => $day->subDays($cookBefore)->format('Y-m-d'),
                ]);
                if ($rationsCol == 2 && $day->addDays($cookBefore)->format('Y-m-d') != $lastDatesArr[$index]) {
                    $secondRation = $ration->replicate();
                    $secondRation->save();
                }
            }
        }
    }


    /**
     * @return Factory|View|Application
     */
    public function showOrders()
    {
        return view('orders', ['orders' => Order::paginate(15)]);
    }

    /**
     * @param int $id
     * @return Factory|View|Application
     */
    public function showOrderRations(int $id)
    {
        return view('orderRations', ['id' => $id, 'rations' => Ration::where('order_id', '=', $id)->paginate(15)]);
    }
}
