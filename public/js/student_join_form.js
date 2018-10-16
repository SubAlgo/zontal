<script type="text/javascript">
    $(document).ready(function () {
        if($("#v_data").val() == 1) {
            $("#v").attr("style", "display")
        }

        if($("#a_data").val() == 1) {
            $("#a").attr("style", "display")
        }

        if($("#k_data").val() == 1) {
            $("#k").attr("style", "display")
        }

        $("#submit").on("click",()=>{
            let class_id = $("#classid").val()
            let std_email = $("#stdemail").val()
            let v, a, k;

            //set vak score
            if($("#v_data").val() == 1) {
                v = $("#v_input").val();
            } else {
                v = 0
            }

            if($("#a_data").val() == 1) {
                a = $("#a_input").val();
            } else {
                a = 0
            }

            if($("#k_data").val() == 1) {
                k = $("#k_input").val();
            } else [
                k = 0
            ]

            //set ข้อมูลคะแนนรายวิชาที่ต้องผ่านก่อนหน้า โดย
            //1. หาจำนวนรายวิชาที่ต้องผ่านทั้งหมด (nOfSubReq)
            //2. สร้าง array (sReq) สำหรับเก็บข้อมูลคะแนนวิชาที่ต้องผ่าน
            
            let nOfSubReq = $("#nOfSubjectReq").val(); //จำนวนวิชาที่ต้องผ่าน
            let sReq =[];
            let i;
            let testVal;
            for(i = 1; i <= nOfSubReq; i++) {
                if($("#sub"+i).val() == "") {
                    alert("กรุณากรอกข้อมูลให้ครบ")
                    return false
                }
                
                sReq.push($("#sub"+i).val());
            }
            //alert(JSON.stringify(sReq))

            //alert(nOfSubReq)
            let d = {   "classid"       : class_id,
                        "std_email"     : std_email,
                        "v"             : v,
                        "a"             : a,
                        "k"             : k,
                        "nOfScore"      : nOfSubReq,
                        "scoreReq"      : sReq

                    };
            //let dumy = JSON.stringify(d)
            console.log(d)
            //alert(d['classid'])

             $.ajax({
                url: 'ajax_student_join_form.php',
                type: 'post',
                data: d,
                success: function(result) {
                    alert(result)
                    window.location.replace("./student_join_class.php");
                }
            });


        })
    });
</script>