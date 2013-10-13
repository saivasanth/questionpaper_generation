<?php
include_once 'Header.php';

if(!isset($_GET['questionId']) || $_GET['questionId'] <= 0)
{
    echo "Error Occurred. Please contact the administrator.";
    die;
}

$questionId = $_GET['questionId'];

$sql = "SELECT * FROM qpg_class_master";
$classes = GetAll($sql);

$sql = "SELECT * FROM qpg_subject_master";
$subjects = GetAll($sql);

$sql = "SELECT * FROM qpg_question_type_master";
$questionTypes = GetAll($sql);

$sql = "SELECT * FROM qpg_difficulty_master";
$difficulties = GetAll($sql);

$sql = "SELECT cq.*,ccm.subject_id,ccm.class_id FROM qpg_question cq
        LEFT JOIN qpg_chapter_master ccm ON ccm.id = cq.chapter_id WHERE cq.id='$questionId'";
$questionDets = GetRow($sql);
?>
<script type="text/javascript">
addQuestionInit();
QuestionChanged();
getChapters('2');
</script>
<div class="qpgContent">
    <div class="qpgQuestionFunctions">
        <table align="center">
            <tr>
                <td>
                    <select id="qpgQuestionType">
                        <?php
                        foreach($questionTypes as $questionType)
                        {
                        ?>
                        <option value="<?php echo $questionType['id']; ?>" <?php if($questionDets['question_type_id'] == $questionType['id']) { echo "selected='selected'"; } ?>><?php echo $questionType['name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id="qpgDifficulty">
                        <?php
                        foreach($difficulties as $difficulty)
                        {
                        ?>
                        <option value="<?php echo $difficulty['id']; ?>" <?php if($questionDets['difficulty_id'] == $difficulty['id']) { echo "selected='selected'"; } ?>><?php echo $difficulty['name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id="qpgClass" onChange="getChapters('2')">
                        <?php
                        foreach($classes as $class)
                        {
                        ?>
                        <option value="<?php echo $class['id']; ?>" <?php if($questionDets['class_id'] == $class['id']) { echo "selected='selected'"; } ?>><?php echo $class['class_name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id="qpgSubject" onChange="getChapters('2')">
                        <?php
                        foreach($subjects as $subject)
                        {
                        ?>
                        <option value="<?php echo $subject['id']; ?>" <?php if($questionDets['subject_id'] == $subject['id']) { echo "selected='selected'"; } ?>><?php echo $subject['type']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id="qpgChapter" selectedId='<?php echo $questionDets['chapter_id']; ?>'>
                        
                    </select>
                </td>
            </tr>
        </table>
        <table align="center">
            <tr>
                <td>
                    <input type="button" value="Add Text" onclick="addContainer('text')" />
                </td>
                <td>
                    <input type="button" value="Add OR" onclick="addContainer('or')" />
                </td>
                <td>
                    <input type="button" value="Add Image" onclick="addContainer('image')" />
                </td>
                <td>
                    <input type="button" value="Add 1) 2) 3)" onclick="addContainer('1')" />
                </td>
                <td>
                    <input type="button" value="Add i) ii) iii)" onclick="addContainer('i')" />
                </td>
                <td>
                    <input type="button" value="Add a) b) c)" onclick="addContainer('a')" />
                </td>
            </tr>
        </table>
    </div>
    <div style="float: left;position: absolute;">
        <table>
            <tr>
                <td>
                    <input type="button" value="Delete Container" onclick="deleteContainer()" />
                </td>
            </tr>
            <tr>
                <td>
                    <table id="imageSizeDiv" style="display: none;">
                        <tr>
                            <td> Width </td>
                            <td>
                                <input type="text" style="width: 50px;" value="" id="imageWidth" /> Px
                            </td>
                        </tr>
                        <tr>
                            <td> Height </td>
                            <td>
                                <input type="text" style="width: 50px;" value="" id="imageHeight" /> Px
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
    <div class="qpgQuestionContainer">
        <?php
            $questionSeperations = explode("$$*", $questionDets['question']);
            foreach($questionSeperations as $key => $questionPart)
            {
            if(count($questionSeperations) > ($key+1))
            {
                if(substr($questionPart, 0, 1) == "*")
                {
                    $temp = substr($questionPart, 0, 3);
                    if($temp == "*%*")
                    {
                        $type = 2;
                    }
                    elseif($temp == "*$*")
                    {
                        $type = 3;
                        $values = explode("*--*", substr($questionPart, 3));
                    }
                    else
                    {
                        $temp = substr($questionPart, 0, 5);
                        if($temp == "*^*-1")
                        {
                            $type = 4;
                        }
                        elseif($temp == "*^*-i")
                        {
                            $type = 5;
                        }
                        elseif($temp == "*^*-a")
                        {
                            $type = 6;
                        }
                        else
                        {
                            $type = "Unknown";
                        }
                        
                        $values = explode("*^*", substr($questionPart, 5));
                    }
                }
                else
                {
                    $type = 1;
                }
                
                $quesNum = $key + 1;
                if($type == 1)
                {
                    echo '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'. $quesNum .'" num="'. $quesNum .'" desc="text">
                            <textarea class="qpgQuestionTextArea" id="qpgQuestionTextArea_'. $quesNum .'">'. substr($questionPart, 0) .'</textarea>
                        </div>';
                    
                }
                elseif($type == 2)
                {
                    echo '<div class="qpgQuestionDiv qpgQuestionOR" id="qpgQuestionDiv_'. $quesNum .'" num="'. $quesNum .'" desc="*%*">
                            OR
                        </div>';
                }
                elseif($type == 3)
                {
                    echo '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'. $quesNum .'" num="'. $quesNum .'" desc="*$*">
                                <div id="upload_buttons_'. $quesNum .'" class="upload_button"><span id="selectImageName_'. $quesNum .'">Select Image<span></div>
                                <div num="'. $quesNum .'" class="changeWidth" >Change Width OR Height</div>
                            </div>';
                    echo '
                        <script type="text/javascript">
                            editQuestionInit("'. $quesNum .'","'. $values[0] .'", "'. substr($values[1],0,-2) .'", "'. substr($values[2],0,-2) .'");
                        </script>
                    ';
                }
                elseif($type == 4)
                {
                    echo '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'. $quesNum .'" num="'. $quesNum .'" desc="*^*-1">
                            <span style="float:left;position:absolute;margin-left:-15px;">1)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_1_'. $quesNum .'" value="'. $values[0] .'"/><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">2)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_2_'. $quesNum .'" value="'. $values[1] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">3)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_3_'. $quesNum .'" value="'. $values[2] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">4)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_4_'. $quesNum .'" value="'. $values[3] .'" /><br />
                        </div>';
                }
                elseif($type == 5)
                {
                    echo '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'. $quesNum .'" num="'. $quesNum .'" desc="*^*-i">
                            <span style="float:left;position:absolute;margin-left:-15px;">i)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_i_'. $quesNum .'" value="'. $values[0] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">ii)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_ii_'. $quesNum .'" value="'. $values[1] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">iii)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_iii_'. $quesNum .'" value="'. $values[2] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">iv)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_iv_'. $quesNum .'" value="'. $values[3] .'" /><br />
                        </div>';
                }
                elseif($type == 6)
                {
                    echo '<div class="qpgQuestionDiv" id="qpgQuestionDiv_'. $quesNum .'" num="'. $quesNum .'" desc="*^*-a">
                            <span style="float:left;position:absolute;margin-left:-15px;">a)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_a_'. $quesNum .'" value="'. $values[0] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">b)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_b_'. $quesNum .'" value="'. $values[1] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">c)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_c_'. $quesNum .'" value="'. $values[2] .'" /><br />
                            <span style="float:left;position:absolute;margin-left:-15px;">d)</span>
                            <input type="text" class="qpgQuestionTextBox" id="qpgQuestionTextBox_d_'. $quesNum .'" value="'. $values[3] .'" /><br />
                        </div>';
                }
            }
            } ?>
    </div>
    <div style="text-align: center;">
        <input type="button" value="Update Question" onClick="AddQuestionInDB('<?php echo $questionId; ?>')" />
        <input type="button" value="Back" onClick="history.back();" />
    </div>
    <div class="qpgQuestionPreviewContents">
        <h3>Question Preview</h3>
        <div id="qpgQuestionPreviewContainer">
            
        </div>
    </div>
</div>