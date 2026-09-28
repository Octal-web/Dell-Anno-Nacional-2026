@extends('emails.layout')

@section('content')
    <h3 style="margin:0 0 20px;color:#161616;font-size:28px;font-weight:400;line-height:40px;text-align:center;">
        Agradecemos o seu interesse em orçar com a Dell Anno!
    </h3>
    <p style="margin:0 0 30px;color:#161616;font-size:20px;line-height:25px;text-align:center;">
        Entenda nosso atendimento exclusivo:
    </p>
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="color:#161616;font-size:16px;line-height:27px;">
        @foreach ([
            'De segunda a sexta, em até 24h, um de nossos consultores entrará em contato com você.',
            'O atendimento exclusivo acompanha toda sua trajetória até a revenda autorizada Dell Anno mais próxima de você.',
            'Em seguida, a loja dá continuidade para lhe oferecer o melhor orçamento, totalmente sem custo e com todo apoio do atendimento de fábrica!',
        ] as $step)
            <tr>
                <td width="36" valign="top" style="padding:0 10px 10px 0;font-size:24px;">{{ $loop->iteration }}.</td>
                <td valign="top" style="padding:0 0 10px;">{{ $step }}</td>
            </tr>
        @endforeach
    </table>
@endsection
