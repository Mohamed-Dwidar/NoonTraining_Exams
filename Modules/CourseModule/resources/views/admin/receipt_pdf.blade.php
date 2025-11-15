<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
    <style>
        /* @page {
            size: 7in 9.25in;
            margin: 27mm 16mm 27mm 16mm;
        } */

        /* @media print {
            body {
                background-color: #6b7375;
                width: 21cm;
                height: 29.7cm;
                margin: 30mm 45mm 30mm 45mm;
            }
        }*/

        /* @page {
            size: 29.7cm 21cm;
            margin: 0;
        } */

        body {
             /* background: url("/assets/images/cert-22.jpg") no-repeat top center;  */
            /* width: 29.7cm;
            height: 21cm; */
            /* font-family: Arial; */
            position: relative;
            -webkit-background-size: cover;
            -moz-background-size: cover;
            -o-background-size: cover;
            background-size: cover;
            background-color: #FFFFFF;
            font-size: 26px;
            padding: 0;
            margin: 15mm 10mm 30mm 10mm;
            direction: rtl;
        }

        .row {
            float: right;
            width: 100%;
            margin: 0 0px;
            padding: 0;
        }

        .col {
            float: right;
        }

        .text-right {
            text-align: right
        }

        .text-center {
            text-align: center
        }

        .text-left {
            text-align: left
        }

        h1 {
            font-size: 55px;
            margin: 0;
            padding: 0
        }

        label {
            font-weight: bold;
            padding-left: 10px
        }

        .bold {
            font-weight: bold
        }

        .normal {
            font-weight: normal
        }

        p {
            margin: -15px 0;
            padding: 0
        }
    </style>
    <style>
        body {
            /* font-family: 'xbriyaz', sans-serif; */
            font-family: 'lateef', sans-serif;
            /* font-family: 'kfgqpcuthmantahanaskh', sans-serif;             */
            margin: 0;
            padding: 0;
            font-weight: bold;
            padding: 150mm 100mm 30mm 100mm;
        }
    </style>
</head>

<body>
    <div class="row">
        <div class="col text-center" style="width: 40%;">
            <p>
                المملكة العربية السعودية
            </p>
            <p>
                معهد نون للتدريب
            </p>
            <p>
                معتمد من المؤسسة العامة للتدريب التقني و المهني
            </p>
            
        </div>

        <div class="col text-center" style="width: 50%">

        </div>
    </div>



    <div class="row">
        <div class="col text-center" style="width: 100%">
            <h1>
                 ايصال دفع  
                {{$receipt_data['course_name']}}
            </h1>
        </div>
    </div>



    <div class="row">
        <div class="col text-right" style="width: 30%">
            <label>
                يشهد معهد /
            </label>
            <b>
                نون للتدريب
            </b>
        </div>
        <div class="col text-right" style="width: 50%">
            <label>
                بأن
                المتدرب
                /
            </label>
            <b>
                {{$student['name']}}
            </b>
        </div>

        <div class="col text-right" style="width: 20%">
            <label>
                وجنسيته
                :
            </label>
            <b>
                {{$student['nationality']}}
            </b>
        </div>
    </div>

    

    

    <div class="row">
        <div class="col text-right" style="width: 50%">
            <label>
               قام بدفع مبلغ
                :
            </label>
            <b style="font-size: 25px">
                {{$payment['amount']}}
            </b>
        </div>
        <div class="col text-right" style="width: 30%">
            <label>
                طريقة الدفع
                :
            </label>
            <b>
                {{$payment['pay_type']}}
            </b>
        </div>
        
    </div>


    <div class="row">
        <div class="col text-right" style="width: 50%">
            <label>
                 إجمالي المدفوع
                :
            </label>
            <b style="font-size: 25px">
            {{number_format($courses_reg->payments->sum('amount'),2)}}
            </b>
        </div>
        <div class="col text-right" style="width: 30%">
            <label>
                 الباقي
                :
            </label>
            <b style="font-size: 25px">
            {{number_format($courses_reg->price - $courses_reg->payments->sum('amount'),2)}}

            </b>
        </div>
        
    </div>

    
    <div class="row">
        <div class="col text-center normal" style="width: 100%">
            <label>
                والله الموفق
                ،،،،
            </label>
        </div>
    </div>





    <div class="row">
        <div class="col text-right normal" style="width: 50%;font-size: 22px">
            
            <p style="margin-bottom: -5px">
                <label>
                    الموافق
                    :
                </label>
             
                {{$payment['paid_at']}}
                م

            </p>
            
        </div>
    </div>



    <div class="row">
        <div class="col text-center" style="width: 30%">
            <label>
                مدير المعهد
            </label>
        </div>
        <div class="col text-center" style="width: 30%">
            <label>
                ختم المعهد
            </label>
        </div>
        <div class="col text-center" style="width: 40%">
            <span style="font-weight: bold">
                <p>
                    تصديق المؤسسة العامة للتدريب التقني و المهني
                </p>
                <p>
                    مدير التدريب الأهلي بالإدارة العامة للتدريب التقني
                </p>
                <p>
                    و المهني بالمنطقة الشرقية
                </p>
            </span>
        </div>
    </div>





    <div class="row" style="margin: 30px 0 0 0;padding:0">
       
        <div class="col text-center" style="width: 30%">
            &nbsp;
        </div>
        
    </div>


</body>

</html>