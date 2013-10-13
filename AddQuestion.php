<?php
include_once 'Header.php';

$sql = "SELECT * FROM qpg_class_master";
$classes = GetAll($sql);

$sql = "SELECT * FROM qpg_subject_master";
$subjects = GetAll($sql);

$sql = "SELECT * FROM qpg_question_type_master";
$questionTypes = GetAll($sql);

$sql = "SELECT * FROM qpg_difficulty_master";
$difficulties = GetAll($sql);
?>
<script type="text/javascript">
addQuestionInit();
QuestionChanged();
getChapters('1');
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
                        <option value="<?php echo $questionType['id']; ?>"><?php echo $questionType['name']; ?></option>
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
                        <option value="<?php echo $difficulty['id']; ?>"><?php echo $difficulty['name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id="qpgClass" onChange="getChapters('1')">
                        <?php
                        foreach($classes as $class)
                        {
                        ?>
                        <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id="qpgSubject" onChange="getChapters('1')">
                        <?php
                        foreach($subjects as $subject)
                        {
                        ?>
                        <option value="<?php echo $subject['id']; ?>"><?php echo $subject['type']; ?></option>
                        <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <select id="qpgChapter">
                        
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
        <div class="qpgQuestionDiv" id="qpgQuestionDiv_1" num="1" desc="text">
            <textarea class="qpgQuestionTextArea" id="qpgQuestionTextArea_1"></textarea>
        </div>
    </div>
    <div style="text-align: center;">
        <input type="button" value="Add Question" onClick="AddQuestionInDB('0')" />
        <input type="button" value="Clear" onClick="clearData();" />
        <input type="button" value="Reload" onClick="location.reload();" />
    </div>
    <div class="qpgQuestionPreviewContents">
        <h3>Question Preview</h3>
        <div id="qpgQuestionPreviewContainer">
            
        </div>
    </div>
</div>
