<!DOCTYPE html>
<html lang="en">
<?php
    include('config.php');
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- import bootstrap and JS -->
    <?php
        include("./layouts/meta.php");
    ?>

    <!-- Check permission access -->
    <?php
    
    //ถ้าไม่ได้ login
    if(!isset($_SESSION)) {
        header("Location: http://{$url}");
        die();
    }
    
    //ถ้าไม่ใช้ Teacher
    if($_SESSION['permission'] != 2) {
        header("Location: http://{$url}");
        die();
    }
    
    
    ?>
    <!--
    
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    -->
    <title>Create Class</title>
</head>
<style>
    input, textarea {
        background:#e1e7f2;
    }
</style>
<body>
    <?php include('./layouts/header.php'); ?>
    <?php include('./layouts/menu.php'); ?>

    <!-- content -->
    <div class="container-fluid">
        <h3>Create Class</h3>
        
        <div class="container" style="border: 1px solid black">
            <form action="" id="create_class" method="get">
                <input type="hidden" id="teacher" value="<?php echo("{$_SESSION['email']}"); ?>">

                <div class="text-center1 row">
                    <div class="col-md-5">Name of Subject: </div>
                    <div class="col-md-7">
                        <input type="text" size="40" id="subject" name="subject" placeholder="Ex. CS441">
                    </div>
                    <hr>
                </div>

                <div class="text-center1 row">
                    <div class="col-md-5">Description</div>
                    <div class="col-md-7">
                        <textarea rows="4" cols="40" id="description" name="description" form="create_class" placeholder="Ex. Algorithms Analysis and Design."></textarea>
                    </div>
                    <hr>
                </div>

                <div class="text-center1 row">
                    <div class="col-md-5">Password of Class:</div>
                    <div class="col-md-7">
                        <input type="text" size="40" id="password" name="password" placeholder="Make your password 4-6 number.">
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-5">Number of student per group:</div>
                    <div class="col-md-7">
                        <select name="std_limit" id="std_limit">
                            <option value="0">Select</option>
                            <option value="1">1</option>
                            <option value="2">2</option>  
                            <option value="3">3</option>  
                            <option value="4">4</option>  
                            <option value="5">5</option>  
                            <option value="6">6</option>  
                            <option value="7">7</option>  
                            <option value="8">8</option>
                            <option value="9">9</option>
                            <option value="10">10</option>
                            <option value="11">11</option>
                            <option value="12">12</option>
                            <option value="13">13</option>
                            <option value="14">14</option>
                            <option value="15">15</option>
                            <option value="16">16</option>
                            <option value="17">17</option>
                            <option value="18">18</option>
                            <option value="19">19</option>
                            <option value="20">20</option>
                        </select>
                    </div>
                    <hr>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <h6>Score require:</h6>
                    </div>
                </div>

                <!-- VAK Require -->
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-11">
                        <input type="checkbox" name="vak" id="vak" value="true">VAK Test
                    </div>                
                </div>
                
                <div class="row" id="vak_list" style="display:none">
                    <div class="col-md-1"></div>
                    <div class="col-md-11" style="padding-left:50px;">
                        <input type="checkbox" name="v" id="v" value="true">V
                        <input type="checkbox" name="a" id="a" value="true">A
                        <input type="checkbox" name="k" id="k" value="true">K
                    </div>
                </div>

                <!-- Previous Require -->
                <div style="display:none1">
                    <div class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-11">
                            <input type="checkbox" name="previous" id="previous" value="true">Previous score require
                            <input type="text" id="score_req" placeholder="Ex.CS100" disabled style="display:none">
                            <span class="btn btn-primary" id="btn_score" style="display:none">Add</span>
                        </div>
                    </div>

                    

                    <div id="s_list" class="row">
                        <div class="col-md-1"></div>
                        <div class="col-md-11">
                            <ol id="score_list" name="score_list" style="padding-leeft:50px"></ol>
                        </div>
                    </div>
                </div>

                <!-- Time input -->
                <div class="row">
                    <div class="col-md-1"></div>
                    <div class="col-md-11">
                        <input type="checkbox" name="time_limit" id="time_limit">Time limiting Start
                    </div>
                </div>

                <div id="time_set" style="display:none">
                    <div class="row">
                        <div class="col-md-3 text-right">
                           <b>Start: </b>
                        </div>
                        <div class="col-md-9">
                            <input type="datetime-local" id="date_start" name="date_start" value="2018-10-01T08:30">
                        </div>
                        <hr>
                    </div>

                    <div class="row">
                        <div class="col-md-3 text-right">
                           <b>End: </b>
                        </div>
                        <div class="col-md-9">
                            <input type="datetime-local" id="date_end" name="date_end" value="2018-10-01T08:30">
                        </div>
                    </div>
                </div>
                
                <div class="row text-center" style="padding-top:5px">
                    <div class="col-md-12">
                        <!-- <input class="btn btn-primary" type="submit" value="Open"> -->
                        <span class="btn btn-primary" id="btn_open">Open</span>
                        <span class="btn btn-success" id="btn_gen">Generate</span>
                        <span class="btn btn-danger">Close</span>
                    </div>
                </div>
                
            
            </form>
        </div>
    </div>
    
    <script type="text/javascript">
        $(document).ready(function () {
            
            let prev = [];
            // Function สร้างรายวิชาที่ต้องการ
            $("#btn_score").click(() => {
                let text = $("#score_req").val();
                text = text.trim();
                if(text.length == 0) {
                    alert("กรุณาระบุรายชื่อวิชา!!")
                    return false
                }
                //testpush
                prev.push($("#score_req").val());
                $("#score_list").append("<li name='sc_list' id='sc_list'>" + text + "</li>");
                $("#score_req").val("");
            });

            //จัดการเกี่ยวกับ รายการเลือกคะแนน VAK
            $("#vak").on("click", () => {
                let n = $("#vak:checked").length;
                if(n == 0) {
                    $("#vak_list").attr("style", "display:none")
                } else if (n > 0) {
                    $("#vak_list").attr("style", "display");
                }
            });

            //จัดการเกี่ยวกับ ปุ่มเพิ่มรายวิชาที่ต้องการก่อนหน้า
            $("#previous").on("click", () => {
                let n = $("#previous:checked").length;
                if(n != 0) {
                    $("#score_req").attr("style", "display:");
                    $("#btn_score").attr("style", "display");
                    $("#s_list").attr("style", "display");
                    $("#score_req").prop('disabled', false)
                } else if( n == 0) {
                    $("#score_req").attr("style", "display:none");
                    $("#btn_score").attr("style", "display:none");                    
                    $("#score_req").prop('disabled', true)
                    //ลบรายการ list ที่แสดง
                    $("li[name='sc_list']").remove();
                    prev = []

                }
            });

            //จัดการเกี่ยวกับ input กำหนดเวลา
            $("#time_limit").on("click", () => {
                let n = $("#time_limit:checked").length;
                if(n == 0) {
                    //alert("btn not click")
                    $("#time_set").attr("style", "display:none")
                } else if(n != 0) {
                    //alert("btn has clicked")
                    $("#time_set").attr("style", "display:")
                }
            });


            //ปุ่มสำหรับสร้าง class เรียน
            $("#btn_open").on("click", ()=>{
                let subject, desc, pass, perGroup;
                let v = a = k = 0;
                let date_start, date_end
                let teacher = $("#teacher").val();
                //alert(v+"|"+a+"|"+k)

                subject = $("#subject").val();
                desc = $("#description").val();
                pass = $("#password").val();
                perGroup = $("#std_limit").val();

                //checkVAK สำหรับเตรียมข้อมูลก่อนส่งค่า
                //คือ ถ้าเลือก check VAK ถึงจะเข้ามากำหนดค่า

                let checkVAK = $("#vak:checked").length;
                let checkV = $("#v:checked").length;
                let checkA = $("#a:checked").length;
                let checkK = $("#k:checked").length;
                
                if(checkVAK != 0) {
                    if(checkV != 0) {
                        v = v + 1
                    }

                    if(checkA != 0) {
                        a++
                    }

                    if(checkK != 0) {
                        k++
                    }
                }

                //check [Previous score require]
                let checkPrev = $("#previous:checked").length;
                if(checkPrev == 0) {
                    prev = []
                }

                /*จัดการข้อมูลเกี่ยวกับวันที่*/
                let checkDate = $("#time_limit:checked").length;
                if(checkDate == 1) {
                    date_start = $("#date_start").val();
                    date_end = $("#date_end").val();
                }
                
                let data = {teacher: teacher,
                            subject : subject,
                            desc : desc,
                            pass: pass,
                            perGroup: perGroup,
                            v: v,
                            a: a,
                            k: k,
                            prev: [prev],
                            checkDate: checkDate,
                            date_start: date_start,
                            date_end: date_end                
                            };

                /*
                let jso = '{    "employees" : [' +
                                '{ "firstName":"John" , "lastName":"Doe" },' +
                                '{ "firstName":"Anna" , "lastName":"Smith" },' +
                                '{ "firstName":"Peter" , "lastName":"Jones" } ]}';
                */

                $.ajax({
                url: 'ajax_createclass.php',
                type: 'post',
                data: data,
                success: function(result) {
                    alert(result)
                }
            });

                //var obj = JSON.parse(jso);

                //alert(obj)
                //alert(data)
                //alert(data["teacher"])
                //alert(data.email);
                //alert(obj['employees'][0]['firstName'])
                //console.log(obj['employees'][0]['firstName'])
                console.log(data)
                //console.log(JSON.parse(data))
                

                //alert("Subject = " + subject + " desc = " + desc + " pass = " + pass + "perGroup = " + perGroup)
                //alert("v: " + v + "| a: "  +  a + "| k: " + k)
                //alert("รายวิชาที่ต้องผ่าน : " + prev)
                //alert("Date_start = " + date_start + " | " + date_end)
                //alert(data.teacher)
                //alert(data.prev)
            });

        });
    </script>
    
    <!-- Footer -->
    <div>
        Footer
    </div>
</body>
</html>