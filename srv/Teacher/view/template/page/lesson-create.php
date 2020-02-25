<div>
    新增课程
    <form method="post" action="lesson-create" enctype="multipart/form-data">
        <label>标题<input name="title" type="text"/></label>
        <label>简介<input name="brief" type="text"/></label>
        <label>封面<input name="cover" type="file" accept="image/*"/></label>
        <label>价格<input name="price" type="text"/></label>
        <label>时间<input name="dtm_start" type="text"/></label>
        <label>课时<input name="duration" type="text"/></label>
        <label><input name="创建课程" type="submit"/></label>
    </form>
</div>