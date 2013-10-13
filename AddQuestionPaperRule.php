<?php
include_once 'Header.php';

//$categoryId = $_REQUEST['category_id'];
$categoryId = '57';
$sql = "SELECT * FROM mdl_course_categories order by path";
$classes = GetAll($sql);

$sql = "SELECT * FROM mdl_qpg_complexities";
$complexities = GetAll($sql);

$sql = "SELECT * FROM mdl_qpg_question_type_master where visible='1'";
$questionTypes = GetAll($sql);

$sql = "SELECT DISTINCT ROUND(defaultmark,0) as marks FROM mdl_question where category='$categoryId' ORDER BY marks ASC";
$marks = GetAll($sql);
$questionPaperId = '';
if(isset($_GET['question_paper_id']) && $_GET['question_paper_id'] > 0)
{
    $questionPaperId = $_GET['question_paper_id'];
    $sql = "SELECT * FROM mdl_qpg_question_paper where id='$questionPaperId'";
    $questionPaperItems = GetRow($sql);
}
?>
<script type="text/javascript">
//getChaptersForQP();
getChaptersByClass();
//getCategories();
//getMarkDetails();
//getChaptersForQP();
</script>
<div class="qpgContent">
    <div class="qpgQuestionFunctions">
<!--        <div style="float:right">
            Select From Previous Terms:
            <select disabled="">
                <option>Term1 2012</option>
            </select>
        </div>-->
        <table>
            <tr>
                <td>
                    <h3>Exam Details:</h3>
                </td>
            </tr>
            <tr>
                <td>
                    <table>
                        <tr>
                            <td>
                                Class Name
                            </td>
                            <td>
                                <select class="qpgTextBox" id="qpgClass" onChange="getChaptersByClass()">
                                    <?php
                                    $temp = '';
                                    foreach($classes as $key => $class)
                                    {
                                        if($class['parent'] == "0")
                                        {
                                            $layer = -1;
                                            if($key > 0)
                                            {
                                                echo "</optgroup>";
                                            }
                                            echo "<optgroup label='". $class['name'] ."'>";
                                        }
                                        else
                                        {
                                    ?>
                                    <option value="<?php echo $class['id']; ?>"
                                        <?php
                                            if($questionPaperId != "" && $questionPaperItems['class_id'] == $class['id'])
                                            {
                                                echo " selected='selected'";
                                            }
                                        ?>>
                                        <?php 
                                        if($temp != $class['parent'] && $class['parent'] > $temp) {
                                            $layer++;
                                        }
                                        elseif($temp != $class['parent'] && $class['parent'] < $temp){
                                            $layer--;
                                        }
                                        for($i=0;$i<$layer;$i++)
                                        echo "&nbsp;&nbsp;&nbsp;&nbsp;";
                                        
                                        echo $class['name']; ?></option>
                                    <?php
                                        }
                                        $temp = $class['parent'];
                                    }
                                    if(count($classes) > 0)
                                        echo "</optgroup>";
                                    ?>
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" id="classCheckbox" onclick="getChaptersByClass()"/>
                                Create for Whole Class Type
                            </td>
                        </tr>
                        <tr class="subjectRow">
                            <td>
                                Subject Name
                            </td>
                            <td>
                                <select class="qpgTextBox" id="qpgSubject" onChange="getCategories()">
                                    
                                </select>
                            </td>
                            <td>
                                <input type="checkbox" id="subjectCheckbox" onclick="getChaptersBySubject()"/>
                                Create for Whole Subject Type
                            </td>
                        </tr>
                        <tr class="categoryRow">
                            <td>
                                Category
                            </td>
                            <td>
                                <select class="qpgTextBox" id="qpgCategoryId" onChange="getMarkDetails()">
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Term Name
                            </td>
                            <td>
                                <input type="text" class="qpgTextBox" id="qpgExamTerm"
                                <?php
                                    if($questionPaperId != "")
                                    {
                                        echo "value='". $questionPaperItems['term'] ."'";
                                    }
                                ?>/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Year
                            </td>
                            <td>
                                <input type="text" class="qpgTextBox" id="qpgExamYear"
                                <?php
                                    if($questionPaperId != "")
                                    {
                                        echo "value='". $questionPaperItems['year'] ."'";
                                    }
                                ?>/>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Exam Total Hours
                            </td>
                            <td>
                                <input type="text" class="qpgTextBox" id="qpgExamTime"
                                <?php
                                    if($questionPaperId != "")
                                    {
                                        echo "value='". $questionPaperItems['total_time'] ."'";
                                    }
                                ?>/>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <table>
                                    <tr>
                                        <td></td>
                                        <td>
                                            Easy
                                        </td>
                                        <td>
                                            Medium
                                        </td>
                                        <td>
                                            Hard
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width:110px;">
                                            Complexities
                                        </td>
                                        <td>
                                            <input type="text" class="qpg" style='width:30px;' id="complexity_easy" name="complexity_easy" value='30'/>%
                                        </td>
                                        <td>
                                            <input type="text" class="qpg" style='width:50px;' id="complexity_medium" name='complexity_medium' value='40'/>%
                                        </td>
                                        <td>
                                            <input type="text" class="qpg" style='width:30px;' id="complexity_hard" name='complexity_hard' value='30'/>%
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <h3>Question Rules:</h3>
                </td>
            </tr>
            <tr>
                <td>
                    <table border="1" cellspacing="0" cellpadding="5" style="border-color:#ccc;">
                        <tr>
                            <th> S.No</th>
                            <th> Type of Questions</th>
                            <th> Marks Per Question</th>
                            <th> No of Questions</th>
                            <th> Total Marks</th>
                        </tr>
                        <?php
                        foreach($questionTypes as $keys => $questionType)
                        {
                            $key = $questionType['id'];
                        ?>
                        <tr>
                            <td class="qpgTd">
                                <?php echo $questionType['id']; ?>
                                <input type="hidden" id="qpgQuesType_<?php echo $key; ?>" value="<?php echo $questionType['id']; ?>" />
                            </td>
                            <td class="qpgTd">
                                <?php echo $questionType['name']; ?>
                            </td>
                            <td class="qpgTd">
                                <select style="width:40px;" class="qpgNumBox qpgQuesTypeMark" num="<?php echo $key; ?>" id="qpgQuesTypeMark_<?php echo $key; ?>" onChange="questionNumChanged()">
                                    <?php foreach($marks as $mark) { ?>
                                    <option value="<?php echo $mark['marks']; ?>" <?php if($questionType['default_mark'] == $mark['marks']) { echo "selected='selected'"; } ?>><?php echo $mark['marks']; ?></option>
                                    <?php } ?>
                                </select>
                                <!--<input type="text" class="qpgNumBox qpgQuesTypeMark" num="<?php echo $key; ?>" id="qpgQuesTypeMark_<?php echo $key; ?>" value="<?php echo $questionType['default_mark']; ?>" onKeyUp="questionNumChanged()" />-->
                            </td>
                            <td class="qpgTd">
                                <input type="text" class="qpgNumBox" id="qpgTotalQues_<?php echo $key; ?>" value="0" onKeyUp="questionNumChanged()" />
                            </td>
                            <td class="qpgTd" id="">
                                <span id="qpgTotalMark_<?php echo $key; ?>"></span>
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td>
                                Total
                            </td>
                            <td>
                                
                            </td>
                            <td>
                                
                            </td>
                            <td align="center">
                                <span id="qpgTotalNumOfQues"></span>
                            </td>
                            <td align="center">
                                <span id="qpgTotalMarks"></span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <table>
            <tr>
                <td>
                    <h3>Form of Questions:</h3>
                </td>
            </tr>
            <tr>
                <td id="qpgFormOfQuestions">
                    
                </td>
            </tr>
            <tr>
                <td>
                    <input type="button" value="Next" onClick="AddQuestionPaperRules()" />
                    <input type="button" value="Back" onClick="location.href='QuestionPaperList.php';" />
                </td>
            </tr>
        </table>
    </div>
</div>

