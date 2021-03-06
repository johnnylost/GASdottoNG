<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Order;

class InvalidateDeliveries extends Command
{
    protected $signature = 'reset:deliveries {order}';
    protected $description = 'Annulla tutte le consegne per un ordine';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $order_id = $this->argument('order');
        $order = Order::findOrFail($order_id);

        foreach($order->bookings as $booking) {
            $booking->status = 'pending';
            $booking->deliverer_id = null;
            $booking->delivery = null;
            $booking->save();

            if ($booking->payment)
                $booking->payment->delete();

            $booking->payment_id = null;

            foreach($booking->products as $product) {
                $product->final_price = 0;
                $product->final_transport = 0;
                $product->delivered = 0;
                $product->save();

                foreach($product->variants as $variant) {
                    $variant->delivered = 0;
                    $variant->save();
                }
            }
        }
    }
}
