var CommonId = 0;
function classListInit()
{
    $(function(){
        $(".qpgClassRow:even").addClass("rowEven");
        $(".qpgClassRow:odd").addClass("rowOdd");
        
        $(".qpgClassRow").click(function(){
            getClassValue($(this).attr("id"));
        });
    });
}

function getClassValue(id)
{
    $.post("ajax.php", {"getClassValue":"1","idVal":id}, function(data) {
        if(data != "")
        {
            var json = $.parseJSON(data);
            CommonId = json['id'];
            $("#classIdContainer").html(json['id']);
            $("#classNameContainer").val(json['class_name']);
            $("#qpgClassContainer").show();
        }
    });
}

function updateClassDetails()
{
    var classId = $("#classIdContainer").html();
    var className = $("#classNameContainer").val();
    if(className == "")
    {
        alert("Enter Class Name");
    }
    else
    {
        $.post("ajax.php", {"setClassValue":"1","classId":classId,"className":className}, function(data) {
            location.reload();
        });
    }
}

function updateChapterDetails(type)
{
    var id = $("#idContainer").html();
    var name = $("#nameContainer").val();
    var description = $("#descriptionContainer").val();
    var mark = $("#markContainer").val();
    var visib = $("#visibContainer").val();
    if(name == "")
    {
        alert("Enter Quesion Type Name");
    }
    else
    {
        $.post("ajax.php", {"setQuestionTypeValue":type,"id":id,"name":name,"description":description,"mark":mark,"visib":visib}, function(data) {
            location.reload();
        });
    }
}

function cancelClicked()
{
    $("#qpgClassContainer").hide();
    $("#qpgChapterContainer").hide();
    $("#qpgQuestionContainer").hide();
    $("#qpgQuestionPaperContainer").hide();
}

function loadPage(page,type)
{
    if(type == "1")
    {
        location.href = page+CommonId;
    }
    else
    {
        location.href = page;
    }
}

function chapterListInit()
{
    $(function(){
        $(".qpgChapterRow:even").addClass("rowEven");
        $(".qpgChapterRow:odd").addClass("rowOdd");
        
        $(".qpgChapterRow").click(function(){
            getQuestionTypeValue($(this).attr("id"));
        });
    });
}

function getQuestionTypeValue(id)
{
    $.post("ajax.php", {"getQuestionTypeValue":"1","idVal":id}, function(data) {
        if(data != "")
        {
            var json = $.parseJSON(data);
//            CommonId = json['id'];
            $("#idContainer").html(json['id']);
            $("#nameContainer").val(json['name']);
            $("#descriptionContainer").val(json['description']);
            $("#markContainer").val(json['default_mark']);
            $("#visibContainer").val(json['visible']);
            
            
            $("#qpgChapterContainer").show();
            $("#addChapter").hide();
            $("#updateChapter").show();
            $("#viewQuestions").show();
        }
    });
}

function addChapter()
{
    $("#chapterIdContainer").html('Will Be Generated');
    $("#chapterNameContainer").val('');
    $("#subjectNameContainer").val('');
    $("#qpgChapterContainer").show();
    $("#addChapter").show();
    $("#updateChapter").hide();
    $("#viewQuestions").hide();
}

var QuestionNum = 1;
var selectedQuestionDivNum = "";
function addQuestionInit()
{
    $(function(){
        $(".qpgQuestionTextArea,.qpgQuestionTextBox").keyup(function(){
            QuestionChanged();
        });
        
        $("#imageWidth,#imageHeight").keyup(function(){
            updateImageSize();
        });
        
        $(".qpgQuestionDiv,.changeWidth").click(function(){
//            $(".qpgQuestionDivActive").removeClass("qpgQuestionDivActive");
//            $(this).addClass("qpgQuestionDivActive");
            selectedQuestionDivNum = $(this).attr("num");
            temp(selectedQuestionDivNum);
        });
        
        
    });
}

function temp(num)
{
    if(num != "")
    {
        selectedQuestionDivNum = num;
        if($("#qpgQuestionDiv_"+selectedQuestionDivNum).attr("desc") == "*$*")
        {
            $("#imageSizeDiv").show();
            $("#imageWidth").val(imageWidth[selectedQuestionDivNum]);
            $("#imageHeight").val(imageHeight[selectedQuestionDivNum]);
        }
        else
        {
            $("#imageSizeDiv").hide();
        }
    }
}

function editQuestionInit(QuesNum, src, width, height)
{
    $(function(){
        $('#qpgQuestionImage_' + QuesNum).attr("src",src);
        $('#selectImageName_' + QuesNum).text("Choose Another Image ("+ src +")");
        imageWidth[QuesNum] = width;
        imageHeight[QuesNum] = height;
        $('#qpgQuestionImage_' + QuesNum).css("width",width);
        $('#qpgQuestionImage_' + QuesNum).css("height",height);
        imageUpload(QuesNum);
    });
}

function QuestionChanged()
{
    $(function(){
        var question = "";
        var contentNum = 0;
        $(".qpgQuestionDiv").each(function(){
            contentNum = $(this).attr("num");
            var desc = $(this).attr("desc");
            if(desc == "text")
            {
                question += "<br />" + $("#qpgQuestionTextArea_" + contentNum).val();
            }
            else if(desc == "*%*")
            {
                question += "<br />" + " OR ";
            }
            else if(desc == "*$*")
            {
                question += "<br />" + " <img src='"+ $("#qpgQuestionImage_" +contentNum).attr("src") +"' id='qpgQuestionImage_"+ contentNum +"' width='"+ imageWidth[contentNum] +"' height='"+ imageHeight[contentNum] +"' /> ";
            }
            else if(desc == "*^*-1")
            {
                question += "<br />"+ " 1) "+ $("#qpgQuestionTextBox_1_" +contentNum).val() +"<br /> "
                                    + " 2) "+ $("#qpgQuestionTextBox_2_" +contentNum).val() +"<br /> "
                                    + " 3) "+ $("#qpgQuestionTextBox_3_" +contentNum).val() +"<br /> "
                                    + " 4) "+ $("#qpgQuestionTextBox_4_" +contentNum).val() +"<br /> ";
            }
            else if(desc == "*^*-i")
            {
                question += "<br />"+ " i) "+ $("#qpgQuestionTextBox_i_" +contentNum).val() +"<br /> "
                                    + " ii) "+ $("#qpgQuestionTextBox_ii_" +contentNum).val() +"<br /> "
                                    + " iii) "+ $("#qpgQuestionTextBox_iii_" +contentNum).val() +"<br /> "
                                    + " iv) "+ $("#qpgQuestionTextBox_iv_" +contentNum).val() +"<br /> ";
            }
            else if(desc == "*^*-a")
            {
                question += "<br />"+ " a) "+ $("#qpgQuestionTextBox_a_" +contentNum).val() +"<br /> "
                                    + " b) "+ $("#qpgQuestionTextBox_b_" +contentNum).val() +"<br /> "
                                    + " c) "+ $("#qpgQuestionTextBox_c_" +contentNum).val() +"<br /> "
                                    + " d) "+ $("#qpgQuestionTextBox_d_" +contentNum).val() +"<br /> ";
            }
        });
        $("#qpgQuestionPreviewContainer").html(question);
    });
}

function addContainer(type)
{
    QuestionNum++;
    if(type == "text")
    {
        var Content = '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'+ QuestionNum +'" num="'+ QuestionNum +'" desc="text">\
                            <textarea class="qpgQuestionTextArea" id="qpgQuestionTextArea_'+ QuestionNum +'">\
</textarea>\
                        </div>';
    }
    else if(type == "or")
    {
        var Content = '<div class="qpgQuestionDiv qpgQuestionOR" id="qpgQuestionDiv_'+ QuestionNum +'" num="'+ QuestionNum +'" desc="*%*">\
                            OR\
                        </div>';
    }
    else if(type == "image")
    {
        var Content = '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'+ QuestionNum +'" num="'+ QuestionNum +'" desc="*$*">\
                            <div id="upload_buttons_'+ QuestionNum +'" class="upload_button"><span id="selectImageName_'+ QuestionNum +'">Select Image<span></div>\
                            <div num="'+ QuestionNum +'" class="changeWidth" >Change Width OR Height</div>\
                        </div>';
        imageWidth[QuestionNum] = "100";
        imageHeight[QuestionNum] = "100";
    }
    else if(type == "1")
    {
        var Content = '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'+ QuestionNum +'" num="'+ QuestionNum +'" desc="*^*-1">\
                            <span style="float:left;position:absolute;margin-left:-15px;">1)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_1_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">2)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_2_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">3)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_3_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">4)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_4_'+ QuestionNum +'" /><br />\
                        </div>';
    }
    else if(type == "i")
    {
        var Content = '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'+ QuestionNum +'" num="'+ QuestionNum +'" desc="*^*-i">\
                            <span style="float:left;position:absolute;margin-left:-15px;">i)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_i_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">ii)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_ii_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">iii)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_iii_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">iv)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_iv_'+ QuestionNum +'" /><br />\
                        </div>';
    }
    else if(type == "a")
    {
        var Content = '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'+ QuestionNum +'" num="'+ QuestionNum +'" desc="*^*-a">\
                            <span style="float:left;position:absolute;margin-left:-15px;">a)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_a_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">b)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_b_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">c)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_c_'+ QuestionNum +'" /><br />\
                            <span style="float:left;position:absolute;margin-left:-15px;">d)</span>\
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_d_'+ QuestionNum +'" /><br />\
                        </div>';
    }
    $(".qpgQuestionContainer").append(Content);
    
    addQuestionInit();
    QuestionChanged();
    
    if(type == "image")
    {
        imageUpload(QuestionNum);
    }
}

function deleteContainer()
{
    $("#qpgQuestionDiv_" + selectedQuestionDivNum).remove();
    QuestionChanged();
}

function updateImageSize()
{
    imageWidth[selectedQuestionDivNum] = $("#imageWidth").val();
    imageHeight[selectedQuestionDivNum] = $("#imageHeight").val();
    QuestionChanged();
}

//function imageSelected()
//{
//    var fileName = $("#qpgQuestionFile_" + selectedQuestionDivNum).val().toLowerCase();;
//    var ext = fileName.split('.').pop();
//    if(ext !="jpg" && ext !="jpeg" && ext !="gif" && ext !="png")
//    {
//        alert("Please select a Image");
//        if ($.browser.msie)
//        {
//            $('#qpgQuestionFile_' + selectedQuestionDivNum).replaceWith($('#browseFile').clone());
//        }
//        else
//        {
//            $('#qpgQuestionFile_' + selectedQuestionDivNum).val('');
//        }
//    }
//    else
//    {
//        alert($("#qpgQuestionFile_" + selectedQuestionDivNum).attr("tmp_name"));
//    }
//}

var imageWidth = Array();
var imageHeight = Array();
function imageUpload(QuesNum)
{
    $(function(){
        var btnUpload=$('#upload_buttons_' + QuesNum);
//        var status=$('#status_message');
        new AjaxUpload(btnUpload, {
            action: 'upload-file.php',
            name: 'uploadfile',
            onChange: function(file, ext){
                temp(QuesNum);
            },
            onSubmit: function(file, ext){
                     if (! (ext && /^(jpg|png|jpeg|gif)$/.test(ext))){ 
        // extension is not allowed 
                            alert('Only JPG, PNG or GIF files are allowed');
                            return false;
                    }
//                    status.text('Uploading...');
            },
            onComplete: function(file, response){
                    //On completion clear the status
//                    status.text('');
                    //Add uploaded file to list
                    if(response==="success"){
                            $('#qpgQuestionImage_' + QuesNum).attr("src",'./uploads/'+file);
                            $('#selectImageName_' + QuesNum).text("Choose Another Image ("+ file +")");
                            temp(QuesNum);
                    } else{
                            $('#selectImageName_' + QuesNum).text("Problem Occurred..");
                    }
            }
        });

    });
}

function AddQuestionInDB(questionId)
{
    var htmlVal = $("#qpgQuestionPreviewContainer").html();
    var chapterId = $("#qpgChapter").val();
    var qpgQuestionType = $("#qpgQuestionType").val();
    var qpgDifficulty = $("#qpgDifficulty").val();
    var question = "";
    $(".qpgQuestionDiv").each(function(){
        var num = $(this).attr("num");
        var desc = $(this).attr("desc");
        if(desc == "text")
        {
            question += $("#qpgQuestionTextArea_" + num).val()+ "$$*";
        }
        else if(desc == "*%*")
        {
            question += "*%*"+ "$$*";
        }
        else if(desc == "*$*")
        {
            question += "*$*" + $("#qpgQuestionImage_" +num).attr("src") 
                        + "*--*" + $("#qpgQuestionImage_" +num).css("width")
                        + "*--*" + $("#qpgQuestionImage_" +num).css("height")+ "$$*";
        }
        else if(desc == "*^*-1")
        {
            question += "*^*-1" + $("#qpgQuestionTextBox_1_" +num).val()
                        + "*^*" + $("#qpgQuestionTextBox_2_" +num).val()
                        + "*^*" + $("#qpgQuestionTextBox_3_" +num).val() 
                        + "*^*" + $("#qpgQuestionTextBox_4_" +num).val() + "$$*";
        }
        else if(desc == "*^*-i")
        {
            question += "*^*-i" + $("#qpgQuestionTextBox_i_" +num).val()
                        + "*^*" + $("#qpgQuestionTextBox_ii_" +num).val()
                        + "*^*" + $("#qpgQuestionTextBox_iii_" +num).val() 
                        + "*^*" + $("#qpgQuestionTextBox_iv_" +num).val() + "$$*";
        }
        else if(desc == "*^*-a")
        {
            question += "*^*-a" + $("#qpgQuestionTextBox_a_" +num).val()
                        + "*^*" + $("#qpgQuestionTextBox_b_" +num).val()
                        + "*^*" + $("#qpgQuestionTextBox_c_" +num).val() 
                        + "*^*" + $("#qpgQuestionTextBox_d_" +num).val() + "$$*";
        }
    });
    
    if(questionId == '0')
    {
        $.post("ajax.php", {"insertQuestion":"1","question": question,"htmlVal":htmlVal,"chapterId": chapterId,"qpgQuestionType":qpgQuestionType,"qpgDifficulty":qpgDifficulty}, function(data) {
            if(data != "")
            {
                alert("Question Added Successfully.");
                clearData();
            }
        });
    }
    else
    {
        $.post("ajax.php", {"updateQuestion":"1","questionId":questionId,"question": question,"htmlVal":htmlVal,"chapterId": chapterId,"qpgQuestionType":qpgQuestionType,"qpgDifficulty":qpgDifficulty}, function(data) {
            if(data != "")
            {
                alert("Question Updated Successfully.");
                location.reload();
            }
        });
    }
}

function clearData()
{
    $(".qpgQuestionDiv").each(function(){
        var num = $(this).attr("num");
        var desc = $(this).attr("desc");
        if(desc == "text")
        {
            $("#qpgQuestionTextArea_" + num).val('');
        }
        else if(desc == "*$*")
        {
            $("#qpgQuestionImage_" +num).attr("src",'');
        }
        else if(desc == "*^*-1")
        {
            $("#qpgQuestionTextBox_1_" +num).val('');
            $("#qpgQuestionTextBox_2_" +num).val('');
            $("#qpgQuestionTextBox_3_" +num).val('');
            $("#qpgQuestionTextBox_4_" +num).val('');
        }
        else if(desc == "*^*-i")
        {
            $("#qpgQuestionTextBox_i_" +num).val('');
            $("#qpgQuestionTextBox_ii_" +num).val('');
            $("#qpgQuestionTextBox_iii_" +num).val('');
            $("#qpgQuestionTextBox_iv_" +num).val('');
        }
        else if(desc == "*^*-a")
        {
            $("#qpgQuestionTextBox_a_" +num).val('');
            $("#qpgQuestionTextBox_b_" +num).val('');
            $("#qpgQuestionTextBox_c_" +num).val('');
            $("#qpgQuestionTextBox_d_" +num).val('');
        }
    });
    $("#qpgQuestionPreviewContainer").html('');
}

function getChapters(type)
{
    $(function(){
        var classId = $("#qpgClass").val();
        var subjectId = $("#qpgSubject").val();
        $.post("ajax.php", {"getAllChapters":"1","classId":classId,"subjectId":subjectId}, function(data) {
            if(data != "")
            {
                var json = $.parseJSON(data);
                $("#qpgChapter").empty();
                for(var i=0; i < json.length; i++)
                {
                    option = $('<option></option>').attr("value", json[i]['id']).text(json[i]['chaptername']);
                    $("#qpgChapter").append(option);
                }
                
                if(type == "2")
                {
                    $("#qpgChapter").val($("#qpgChapter").attr("selectedId"));
                }
            }
        });
    });
}

function questionNumChanged()
{
    var totalQues = 0;
    var totalMarks = 0;
    $(".qpgQuesTypeMark").each(function(){
        var num = $(this).attr("num");
        var tempMarks = 0;
        tempMarks = $("#qpgQuesTypeMark_" + num).val() * $("#qpgTotalQues_" + num).val();
        $("#qpgTotalMark_" + num).html(tempMarks);
        totalMarks += tempMarks;
        
        if($("#qpgTotalQues_" + num).val() > 0)
            totalQues += parseInt($("#qpgTotalQues_" + num).val());
    });
    $("#qpgTotalNumOfQues").html(totalQues);
    $("#qpgTotalMarks").html(totalMarks);
}

function AddQuestionPaperRules()
{
//    if($("#qpgExamTerm").val() == "")
//    {
//        alert("Please Enter Exam Term Name");
//    }
//    else if($("#qpgExamYear").val() == "")
//    {
//        alert("Please Enter Exam Year");
//    }
    if($("#qpgExamTime").val() == "")
    {
        alert("Please Enter Exam Total Duration");
    }
    else if((parseInt($("#complexity_easy").val()) + parseInt($("#complexity_medium").val()) + parseInt($("#complexity_hard").val())) != 100)
    {
        alert("Complexity Total should be 100%.");
    }
    else if($("#qpgTotalNumOfQues").html() <= 0)
    {
        alert("Please Enter Number of Questions");
    }
    else
    {
        var classId = $("#qpgClass").val();
        var categoryId = $("#qpgCategoryId").val();
//        var subjectId = $("#qpgSubject").val();
        var examTerm = $("#qpgExamTerm").val();
        var examYear = $("#qpgExamYear").val();
        var examTime = $("#qpgExamTime").val();
        var complexity_easy = $("#complexity_easy").val();
        var complexity_medium = $("#complexity_medium").val();
        var complexity_hard = $("#complexity_hard").val();
        var fullQuesDets = getFullChaptersInString();
        var courseId = $("#qpgSubject").val();
        $.post("ajax.php", {"insertQuestionRules":"1","categoryId":categoryId,"examTerm":examTerm,"classId":classId,
                            "examYear":examYear,"examTime":examTime,"fullQuesDets":fullQuesDets,"courseId":courseId, "complexity_easy":complexity_easy, "complexity_medium":complexity_medium, "complexity_hard":complexity_hard}, function(data) {
            if(data != "")
            {
                var json = $.parseJSON(data);
                location.href = "PrepareQuestions.php?questionPaperId="+json;
            }
        });
    }
}

function getFullChaptersInString()
{
    var ChapValues = "";
    $(".qpgCateg").each(function(){
        var key = $(this).attr("key");
        if($(this).val() > 0)
        {
            var vals = key.split('_');
            if(ChapValues != "")
                ChapValues += "$";
        $("#qpgQuesTypeMark_" + vals[1]).val();
        ChapValues += vals[0] + "*" + vals[1] + "*" + $("#qpgQuesTypeMark_" + vals[1]).val() + "*" + $(this).val();
        }
    });
    return ChapValues;
}

function getChaptersForQP()
{
    $(function(){
        var categoryId = $("#qpgCategoryId").val();
        $.post("ajax.php", {"getAllChapters":"1","categoryId":categoryId}, function(data) {
            if(data != "")
            {
                printChapterRules(data,'1');
            }
        });
    });
}

function printChapterRules(data,type)
{
    var json = $.parseJSON(data);
    $("#qpgFormOfQuestions").html('');
    var table = '<table border="1" cellspacing="0" cellpadding="5" style="border-color:#ccc;border-collapse: collapse;">\
                    <tr>\
                        <th> S.No</th>';
    if(type > 2)
    table +=           '<th> Subject Name</th>';
    if(type > 1)
    table +=           '<th> Category</th>';
    table +=           '<th> Chapters</th>';
    var temp = json[0]['id'];
    for(i=0; i < json.length; i++)
    {
        if(!json[i]['id'] || temp != json[i]['id'])
        {
            break;
        }
        table += '<th> '+ json[i]['question_type'] +'</th>';
    }
        table += '<th> Total</th>\
                </tr>';
    var k = -1;
    var key = '';
    var col = '';
    var row = '';
    for(var i=0; i < json.length; i++)
    {
        k++;
        var c = -1;
        table += '<tr>\
                        <td class="qpgTd">\
                            '+ (k + 1) +'\
                        </td>';
        if(type > 2)            
        table +=       '<td class="qpgTd">\
                            '+ json[i]['subjectname'] +'\
                        </td>';
        if(type > 1)            
        table +=       '<td class="qpgTd">\
                            '+ json[i]['categoryname'] +'\
                        </td>';
        
        table +=       '<td class="qpgTd">\
                            '+ json[i]['chaptername'] +'\
                        </td>';
        temp = json[i]['id'];

        for(j=i; j < json.length; j++)
        {
            key = json[j]['id'] + '_' + json[j]['question_type_id'];
            c++;
            if(!json[j]['id'] || temp != json[j]['id'])
            {
                break;
            }
            table += '<td class="qpgTd">\
                        <input type="text" class="qpgCateg qpgNumBox qpgQuesForm'+ k +'" key="'+ key +'" col="'+ c +'" row="'+ k +'" chapterid="'+ json[i]['id'] +'" id="qpgQuesTypeNum_'+ key +'" value="0" onKeyUp="questionFormNumChanged()" />\
                      </td>';
        }
            i = --j;
        table += '<td class="qpgTd" id="">\
                            <span id="qpgTotalQuesRow_'+ k +'"></span>\
                        </td>\
                    </tr>';
    }
    table += '<tr>\
                <td></td>';
    if(type > 2)
      table += '<td></td>';
    if(type > 1)
      table += '<td></td>';
    table +=   '<td>\
                    Total\
                </td>';
    var temp = json[0]['id'];
    var x = -1;
    for(i=0; i < json.length; i++)
    {
        x++;
        if(!json[i]['id'] || temp != json[i]['id'])
        {
            break;
        }
        table += '<td>\
                    <span id="qpgTotalQuesCol_'+ x +'"></span>\
                  </td>';
    }
            table += '<td>\
                    <span id="qpgFormTotalNumOfQues"></span>\
                </td>\
            </tr>\
        </table>';
    $("#qpgFormOfQuestions").html(table);
}

function questionFormNumChanged()
{
    var totalQuestions = 0;
    var totalRow = Array();
    var totalCol = Array();
    $(".qpgCateg").each(function(){
        var num = $(this).attr("key");
        var col = $(this).attr("col");
        var row = $(this).attr("row");
        var tempMarks = 0;

        var value = parseInt($(this).val());
        if(!totalRow[row])
        {
            totalRow[row] = 0;
        }
        if(!totalCol[col])
        {
            totalCol[col] = 0;
        }
        totalRow[row] += value;
        totalCol[col] += value;
    });
    for(var i=0; i < totalRow.length; i++)
    {
        $("#qpgTotalQuesRow_" + i).html(totalRow[i]);
        totalQuestions += totalRow[i];
    }
    for(var i=0; i < totalCol.length; i++)
    {
        $("#qpgTotalQuesCol_" + i).html(totalCol[i]);
    }
    $("#qpgFormTotalNumOfQues").html(totalQuestions);
}

function questionListInit()
{
    $(function(){
        $(".qpgQuestionRow:even").addClass("rowEven");
        $(".qpgQuestionRow:odd").addClass("rowOdd");
        
        $(".qpgQuestionRow").click(function(){
            getQuestionValue($(this).attr("id"));
        });
    });
}

function getQuestionValue(id)
{
    $.post("ajax.php", {"getQuestionValue":"1","idVal":id}, function(data) {
        if(data != "")
        {
            var json = $.parseJSON(data);
            CommonId = json['id'];
            $("#questionIdContainer").html(json['id']);
            $("#questionContainer").html(json['question_html']);
            $("#qpgQuestionContainer").show();
        }
    });
}

function questionPaperListInit()
{
    $(function(){
        $(".qpgQuestionPaperRow:even").addClass("rowEven");
        $(".qpgQuestionPaperRow:odd").addClass("rowOdd");
        
        $(".qpgQuestionPaperRow").click(function(){
            getQuestionPaperValue($(this).attr("id"));
        });
    });
}

function getQuestionPaperValue(id)
{
    $.post("ajax.php", {"getQuestionRuleValue":"1","idVal":id}, function(data) {
        if(data != "")
        {
            var json = $.parseJSON(data);
            CommonId = id;
            var table = '<table border="1" cellspacing="0" cellpadding="5" style="border-color:#ccc;">\
                                <tr>\
                                    <th> S.No</th>\
                                    <th> Form of Questions</th>\
                                    <th> VSA</th>\
                                    <th> SA-I</th>\
                                    <th> SA-II</th>\
                                    <th> LA</th>\
                                    <th> MCQ</th>\
                                    <th> Total</th>\
                                </tr>';
                var FullTotal = 0;
                var col1Total = 0;
                var col2Total = 0;
                var col3Total = 0;
                var col4Total = 0;
                var col5Total = 0;
                for(var i=0; i < json.length; i++)
                {
                    var tempValues = json[i]['chapterValues'].split(',');
                    var chapterValue = Array('0','0','0','0','0','0');
                    var total = 0;
                    for(var j=0;j< tempValues.length; j++)
                    {
                        var temp = tempValues[j].split('-');
                        chapterValue[temp[0]] = temp[1];
                        total += parseInt(temp[1]);
                    }
                    col1Total += parseInt(chapterValue[1]);
                    col2Total += parseInt(chapterValue[2]);
                    col3Total += parseInt(chapterValue[3]);
                    col4Total += parseInt(chapterValue[4]);
                    col5Total += parseInt(chapterValue[5]);
                    FullTotal += total;
                    table += '<tr>\
                                    <td class="qpgTd">\
                                        '+ parseInt(i + 1) +'\
                                    </td>\
                                    <td class="qpgTd">\
                                        '+ json[i]['chaptername'] +'\
                                    </td>\
                                    <td class="qpgTd">\
                                        '+ chapterValue[1] +'\
                                    </td>\
                                    <td class="qpgTd">\
                                        '+ chapterValue[2] +'\
                                    </td>\
                                    <td class="qpgTd">\
                                        '+ chapterValue[3] +'\
                                    </td>\
                                    <td class="qpgTd">\
                                        '+ chapterValue[4] +'\
                                    </td>\
                                    <td class="qpgTd">\
                                        '+ chapterValue[5] +'\
                                    </td>\
                                    <td class="qpgTd" id="">\
                                        <b>'+ total +'</b>\
                                    </td>\
                                </tr>';
                }
                table += '<tr>\
                            <td></td>\
                            <td>\
                                Total\
                            </td>\
                            <td class="qpgTd">\
                                <b>'+ col1Total +'</b>\
                            </td>\
                            <td class="qpgTd">\
                                <b>'+ col2Total +'</b>\
                            </td>\
                            <td class="qpgTd">\
                                <b>'+ col3Total +'</b>\
                            </td>\
                            <td class="qpgTd">\
                                <b>'+ col4Total +'</b>\
                            </td>\
                            <td class="qpgTd">\
                                <b>'+ col5Total +'</b>\
                            </td>\
                            <td class="qpgTd">\
                                <b>'+ FullTotal +'</b>\
                            </td>\
                        </tr>';
            table += '</table>';
            table += '<input type="hidden" id="questionPaperId" value="'+ id +'">';
                $("#qpgQuestionPaperRules").html(table);
            $("#qpgQuestionPaperContainer").show();
        }
    });
}

function changeQuesion(chapterId, questionType, key)
{
    $.post("ajax.php", {"getQuestion":"1","questionType":questionType,
    "chapterId":chapterId}, function(data) {
        if(data != "")
        {
            var json = $.parseJSON(data);
            $("#questionid_"+key).html(json['question_html']);
            $("#questionid_"+key).attr("question_id",json['id']);
        }
    });
}

function changeQuesion_select(chapterId, questionType, key)
{
    $.post("ajax.php", {"getQuestions":"1","questionType":questionType,
    "chapterId":chapterId}, function(data) {

        if(data != "")
        {
            var json = $.parseJSON(data);
//            alert(json);
            var content = '<table border="0">'
            for(var i=0; i< json.length; i++)
            {
                if(json[i]['complexity_id'] == 1)
                {
                    var imgHTML = '<img src="images/easy.png" alt="E" title="Easy Question"  style="width:16px;" />';
                }
                else if(json[i]['complexity_id'] == 2)
                {
                    var imgHTML = '<img src="images/medium.png" alt="M" title="Medium Question"  style="width:16px;" />';
                }
                else
                {
                    var imgHTML = '<img src="images/hard.png" alt="H" title="Hard Question"  style="width:16px;" />';
                }
                content += '<tr class="qpgQuestionPaperRow" key="'+ key +'" cqpr_id="'+ json[i]['id'] +'">\
                                <td>\
                                    ' + json[i]['id'] + '\
                                </td>\
                                <td>\
                                    ' + imgHTML + '\
                                </td>\
                                <td id="popup_question_'+ json[i]['id'] +'">\
                                    '+ json[i]['question_html'] +'\
                                </td>\
                            </tr>';
                content += '<tr>\
                                <td colspan="2">\
                                    <hr>\
                                </td>\
                            </tr>';
            }
            
            if(json.length <= 0)
            {
                content += '<tr class="" id="">\
                                <td>\
                                    No Questions Available in Database\
                                </td>\
                            </tr>';
            }
            
            content += "</table>";
            
            $('#popup_content').html(content);
            
            selectedQuestion = 0;
            selectedKey = 0;

            loadPopupBox();
        }
    });
}

function saveClicked_questionpaper()
{
    var questions = Array();
    $(".questions").each(function(){
        if($(this).attr("question_id") != "0")
        questions[questions.length] = $(this).attr("question_id");
    });
    var questions_ruleid = Array();
    $(".questions").each(function(){
        if($(this).attr("question_id") != "0")
        questions_ruleid[questions_ruleid.length] = $(this).attr("question_paper_rules_id");
    });
//    alert(questions);
    
    $.post("ajax.php", {"saveQuestionPaper":"1","questions":questions,"questions_ruleid":questions_ruleid}, function(data) {
        alert("Updated Successfully");
        location.reload();
    });
}

function PushQuestionToDiv()
{
    if(selectedQuestion == 0)
    {
        alert("Please select a Question");
    }
    else
    {
        $("#questionid_"+selectedKey).html($("#popup_question_"+ selectedQuestion).html());
        $("#questionid_"+selectedKey).attr("question_id",selectedQuestion);
        unloadPopupBox();
    }
}
var selectedQuestion = 0;
var selectedKey = 0;
// POPUP Start
function popupinit()
{
$(document).ready( function() {

// When site loaded, load the Popupbox First
//loadPopupBox();

$('#popupBoxClose').click( function() {			
        unloadPopupBox();
});

$('#mainContainer').click( function() {
        unloadPopupBox();
});

/**********************************************************/

});
}

function unloadPopupBox() {	// TO Unload the Popupbox
        $('#popup_box').fadeOut("slow");
        $("#mainContainer").css({ // this is just for style		
                "opacity": "1"  
        }); 
}	

function loadPopupBox() {	// To Load the Popupbox
    var height = $(window).height();
    var width = $(window).width();

    var popUpWidth = $("#popup_box").width();
    var popUpHeight = $("#popup_box").height();
    var popUpTop = height/2 - popUpHeight/2;
    var popUpLeft = width/2 - popUpWidth/2 - 5;

    $("#popup_box").css({"top":popUpTop+"px","left":popUpLeft+"px"});
    $('#popup_box').fadeIn("slow");
    $("#mainContainer").css({ // this is just for style
            "opacity": "0.3"  
    });
    
    $('.qpgQuestionPaperRow').click(function(){
        selectedQuestion = $(this).attr("cqpr_id");
        selectedKey = $(this).attr("key");
        $('.qpgQuestionPaperRow').removeClass('active');
        $(this).addClass('active');
    });
}
// Popup End

function deleteQuestion()
{
    var confirmValue = confirm("Do you really want to Delete this Question?");
    if(confirmValue)
    {
        var questionId = $("#questionIdContainer").html();
        $.post("ajax.php", {"deleteQuestion":"1","questionId":questionId}, function(data) {
                alert("Question Deleted Successfully");
                location.reload();
        });
    }
}

function deleteChapter()
{
    var confirmValue = confirm("By Deleting the chapter it will delete all the questions inside this Chapter, \
Do you want to Proceed?");
    if(confirmValue)
    {
        var chapterId = $("#chapterIdContainer").html();
        $.post("ajax.php", {"deleteChapter":"1","chapterId":chapterId}, function(data) {
                alert("Chapter Deleted Successfully");
                location.reload();
        });
    }
}

function getMarkDetails()
{
    updateMarkDetails();
    getChaptersForQP();
}

function updateMarkDetails()
{
    var categoryId = 0;
    var type = 0;
    if($("#classCheckbox").is(':checked'))
    {
        categoryId = $("#qpgClass").val();
        type = '3';
    }
    else if($("#subjectCheckbox").is(':checked'))
    {
        categoryId = $("#qpgSubject").val();
        type = '2';
    }
    else
    {
        categoryId = $("#qpgCategoryId").val();
        type = '1';
    }
    
        $.post("ajax.php", {"getMarks":"1","categoryId":categoryId,"type":type}, function(data) {
            var option = "";
            var json = $.parseJSON(data);

            $(".qpgQuesTypeMark").each(function (){
                var key = $(this).attr('num');
                $("#qpgQuesTypeMark_" + key).empty();
                for(i=0; i<json.length; i++)
                {
                    option = $('<option></option>').attr("value", json[i]['marks']).text(json[i]['marks']);
                    $("#qpgQuesTypeMark_" + key).append(option);
                }
            });
        });
}

function getCourses()
{
    $(function(){
        var classId = $("#qpgClass").val();
        $.post("ajax.php", {"getAllCourses":"1","classId":classId}, function(data) {
            if(data != "")
            {
                var json = $.parseJSON(data);
                $("#qpgSubject").empty();
                for(var i=0; i < json.length; i++)
                {
                    option = $('<option></option>').attr("value", json[i]['id']).text(json[i]['name']);
                    $("#qpgSubject").append(option);
                }
            }
            
            getCategories();
        });
    });
}

function getCategories()
{
    $(function(){
        var courseId = $("#qpgSubject").val();
        $.post("ajax.php", {"getAllCategories":"1","courseId":courseId}, function(data) {
            if(data != "")
            {
                var json = $.parseJSON(data);
                $("#qpgCategoryId").empty();
                for(var i=0; i < json.length; i++)
                {
                    option = $('<option></option>').attr("value", json[i]['id']).text(json[i]['name']);
                    $("#qpgCategoryId").append(option);
                }
            }
            getMarkDetails();
//            getChaptersForQP();
        });
    });
}

function getChaptersByClass()
{
    if($("#classCheckbox").is(':checked'))
    {
        $(".subjectRow").hide();
        $(".categoryRow").hide();
        $(function(){
            var classId = $("#qpgClass").val();
            $.post("ajax.php", {"getAllChapters":"1","classId":classId}, function(data) {
                if(data != "")
                {
                    printChapterRules(data,'3');
                }
            });
            updateMarkDetails();
        });
    }
    else
    {
        $(".subjectRow").show();
        if(!$("#subjectCheckbox").is(':checked'))
            $(".categoryRow").show();
        getCourses();
    }
}

function getChaptersBySubject()
{
    if($("#subjectCheckbox").is(':checked'))
    {
        $(".categoryRow").hide();
        $(function(){
            var subjectId = $("#qpgSubject").val();
            $.post("ajax.php", {"getAllChapters":"1","subjectId":subjectId}, function(data) {
                if(data != "")
                {
                    printChapterRules(data,'2');
                }
            });
            updateMarkDetails();
        });
    }
    else
    {
        $(".categoryRow").show();
        getCourses();
    }
}

function deleteClicked()
{
    if(confirm("Do you really want to delete this Question Paper?"))
    {
        var questionPaperId = $("#questionPaperId").val();
        $.post("ajax.php", {"deleteQuestionPaper":"1","questionPaperId":questionPaperId}, function(data) {
            alert("Question Paper Deleted Successfully");
            location.reload();
        });
    }
}