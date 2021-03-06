<?php $summary = $order->calculateSummary() ?>

<form class="form-horizontal main-form order-editor" method="PUT" action="{{ route('orders.update', $order->id) }}">
    <input type="hidden" name="order_id" value="{{ $order->id }}" />

    <div class="row">
        <div class="col-md-4">
            @include('commons.staticobjfield', ['obj' => $order, 'name' => 'supplier', 'label' => _i('Fornitore')])
            @include('commons.staticstringfield', ['obj' => $order, 'name' => 'internal_number', 'label' => _i('Numero')])

            @if(in_array($order->status, ['suspended', 'open', 'closed']))
                @include('commons.textfield', ['obj' => $order, 'name' => 'comment', 'label' => _i('Commento')])
                @include('commons.datefield', ['obj' => $order, 'name' => 'start', 'label' => _i('Data Apertura'), 'mandatory' => true])

                @include('commons.datefield', [
                    'obj' => $order,
                    'name' => 'end',
                    'label' => _i('Data Chiusura'),
                    'mandatory' => true,
                    'extras' => [
                        'data-enforce-after' => '.date[name=start]'
                    ]
                ])

                @include('commons.datefield', [
                    'obj' => $order,
                    'name' => 'shipping',
                    'label' => _i('Data Consegna'),
                    'extras' => [
                        'data-enforce-after' => '.date[name=end]'
                    ]
                ])
            @else
                @include('commons.staticstringfield', ['obj' => $order, 'name' => 'comment', 'label' => _i('Commento')])
                @include('commons.staticdatefield', ['obj' => $order, 'name' => 'start', 'label' => _i('Data Apertura')])
                @include('commons.staticdatefield', ['obj' => $order, 'name' => 'end', 'label' => _i('Data Chiusura')])
                @include('commons.staticdatefield', ['obj' => $order, 'name' => 'shipping', 'label' => _i('Data Consegna')])
            @endif

            @include('commons.orderstatus', ['order' => $order])
        </div>
        <div class="col-md-4">
            @if(in_array($order->status, ['suspended', 'open', 'closed']))
                @include('commons.percentagefield', ['obj' => $order, 'name' => 'discount', 'label' => _i('Sconto Globale')])
                @include('commons.percentagefield', ['obj' => $order, 'name' => 'transport', 'label' => _i('Spese Trasporto')])
            @else
                @include('commons.staticpercentagefield', ['obj' => $order, 'name' => 'discount', 'label' => _i('Sconto Globale')])
                @include('commons.staticpercentagefield', ['obj' => $order, 'name' => 'transport', 'label' => _i('Spese Trasporto')])
            @endif

            @include('commons.movementfield', [
                'obj' => $order->payment,
                'name' => 'payment_id',
                'label' => _i('Pagamento'),
                'default' => \App\Movement::generate('order-payment', $currentgas, $order, $summary->price_delivered + $summary->transport_delivered),
                'to_modal' => [
                    'amount_editable' => true
                ]
            ])
        </div>
        <div class="col-md-4">
            @include('order.files', ['order' => $order])
        </div>
    </div>

    <hr>

    @include('order.summary', ['order' => $order, 'summary' => $summary])
    @include('order.annotations', ['order' => $order, 'summary' => $summary])

    @include('commons.formbuttons', [
        'left_buttons' => [
            (object) [
                'label' => _i('Esporta'),
                'url' => $order->exportableURL(),
                'class' => ''
            ]
        ]
    ])
</form>
