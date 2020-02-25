<div class="portlet box blue">
    <div class="portlet-title">
        <div class="caption">用户列表</div>
        <div class="actions">
            <div class="input-group" style="width:300px">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input  name="email" class="form-control" placeholder="Invite User"/>
                <span class="input-group-btn" id="invite-user">
                    <button type="submit" class="btn btn-default">+</button>
                </span>
            </div>
        </div>
    </div>
    <div class="portlet-body portlet-compact">
        <div class="table-scrollable">
            <table class="table table-bordered table-hover">
                <thead>
                <tr>
                    <td>用户ID</td>
                    <td>role</td>
                    <td>SN</td>
                    <td>名称</td>
                    <td>信息</td>
                    <td>设置</td>
                    <td>创建时间</td>
                    <td>更新时间</td>

                </tr>
                </thead>
                <tbody>
                <?php foreach ($this->list as $item) { ?>
                    <tr>
                        <td><?=$item['id']?></td>
                        <td><?=$this->role_map[$item['i_role']]?></td>
                        <td><?=$item['sn']?></td>
                        <td><?=$item['name']?></td>
                        <td><?=$item['info']?></td>
                        <td><?=$item['setting']?></td>
                        <td><?=$item['tms_create']?></td>
                        <td><?=$item['tms_update']?></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

