<?php
include_once 'Header.php';

$sql = "SELECT *
    FROM mdl_qpg_question_type_master cqtm";
$questionTypes = GetAll($sql);

?>
<script type="text/javascript">
chapterListInit();
</script>
<div class="qpgContent">
    <div class="qpgChapterList">
        <table style="border:1px solid #cccccc;width:500px;" cellspacing="0" align="center">
            <tr style="background: #0099FF;">
                <td>
                    S.No
                </td>
                <td>
                    Question Type
                </td>
                <td>
                    Description
                </td>
                <td>
                    Default Mark
                </td>
                <td>
                    Status
                </td>
            </tr>
        <?php
        foreach($questionTypes as $key => $questionType)
        {
        ?>
            <tr class="qpgChapterRow" id="<?php echo $questionType['id'] ?>">
                <td>
                    <?php echo ($key + 1); ?>
                </td>
                <td>
                    <?php echo $questionType['name']; ?>
                </td>
                <td>
                    <?php echo $questionType['description']; ?>
                </td>
                <td>
                    <?php echo $questionType['default_mark']; ?>
                </td>
                <td>
                    <?php if($questionType['visible']){ echo "Visible"; } else { echo "Hidden";}; ?>
                </td>
            </tr>
        <?php
        }
        ?>
        </table>
        <div style="text-align: center;">
            <input type="button" value="Add Question Type" onclick="addChapter()" />
            <input type="button" value="Back" onclick="location.href='QuestionPaperList.php';" />
        </div>
    </div>
    <div class="qpgChapterContents">
        <div id="qpgChapterContainer">
            <br />
            <table style="width:400px;border-collapse: collapse;" align="center" border="1" cellspacing="0" cellpadding="10">
                <tr>
                    <td>
                        ID
                    </td>
                    <td>
                        <span id="idContainer">
                            
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Name
                    </td>
                    <td>
                        <input type="text" id="nameContainer" value="" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Description
                    </td>
                    <td>
                        <input type="text" id="descriptionContainer" value="" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Default Mark
                    </td>
                    <td>
                        <input type="text" id="markContainer" value="" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Visibility
                    </td>
                    <td>
                        <select id="visibContainer">
                            <option value="1">Visible</option>
                            <option value="0">Hidden</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="button" value="Add" id="addChapter" onclick="updateChapterDetails('1')" />
                        <input type="button" value="Update" id="updateChapter" onclick="updateChapterDetails('2')" />
                        <input type="button" value="Cancel" onclick="cancelClicked()" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>