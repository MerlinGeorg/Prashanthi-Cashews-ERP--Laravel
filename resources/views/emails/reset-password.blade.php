<html>
    <head><title>{{$data['subject']}}</title></head>
    <body>
        <div style="background:#f8f8f8;height:auto;padding: 40px;font-family: arial,sans-serif;">
            <a href="{{url('/')}}">{{config('app.name')}}</a>
            <div style="background:#fff;height:auto;padding:20px 40px;border:1px solid #dce1e5;">
                <p> Hi <strong>{{$data['name']}},</strong></p>
                <br>
                <p>
                    {!! $data['message'] !!}
                </p>
                <div>
                    <br>
                    <h5>Thanks, </h5>
                    <p>Softball Team</p>
                </div>

            </div>
        </div>
    </body>
</html>