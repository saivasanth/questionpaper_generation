<?php
include_once 'Header.php';

$classId = $_REQUEST['classId'];
$sql = "SELECT ccm.id,ccm.name as chaptername, csm.type as subjectname
    FROM qpg_chapter_master ccm
    LEFT JOIN qpg_subject_master csm ON csm.id=ccm.subject_id
    WHERE ccm.class_id='" . $classId . "'";
$chapters = GetAll($sql);

$sql = "SELECT * FROM qpg_subject_master ORDER BY id asc";
$subjects = GetAll($sql);

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
                    Chapter Name
                </td>
                <td>
                    Subject Name
                </td>
                <td>
                    Action
                </td>
            </tr>
        <?php
        foreach($chapters as $key => $chapter)
        {
        ?>
            <tr class="qpgChapterRow" id="<?php echo $chapter['id'] ?>">
                <td>
                    <?php echo ($key + 1); ?>
                </td>
                <td>
                    <?php echo $chapter['chaptername']; ?>
                </td>
                <td>
                    <?php echo $chapter['subjectname']; ?>
                </td>
                <td>
                    View
                </td>
            </tr>
        <?php
        }
        ?>
        </table>
        <div style="text-align: center;">
            <input type="button" value="Add Chapter" onclick="addChapter()" />
            <input type="button" value="Back" onclick="location.href='ClassList.php';" />
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
                        <span id="chapterIdContainer">
                            
                        </span>
                    </td>
                </tr>
                <tr>
                    <td>
                        Chapter Name
                    </td>
                    <td>
                        <input type="text" id="chapterNameContainer" value="" />
                    </td>
                </tr>
                <tr>
                    <td>
                        Subject Name
                    </td>
                    <td>
                        <select id="subjectNameContainer">
                            <?php foreach($subjects as $subject) { ?>
                            <option value="<?php echo $subject['id']; ?>"><?php echo $subject['type']; ?></option>
                            <?php } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" align="center">
                        <input type="button" value="Add" id="addChapter" onclick="updateChapterDetails('<?php echo $classId; ?>','1')" />
                        <input type="button" value="Update" id="updateChapter" onclick="updateChapterDetails('<?php echo $classId; ?>','2')" />
                        <input type="button" value="Cancel" onclick="cancelClicked()" />
                        <input type="button" value="View All Questions" id="viewQuestions" onclick="loadPage('QuestionList.php?chapterId=','1')" />
                        <input type="button" value="Delete" onclick="deleteChapter()" />
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>