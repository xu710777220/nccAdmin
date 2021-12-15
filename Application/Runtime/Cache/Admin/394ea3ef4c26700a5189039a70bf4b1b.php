<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>layui</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/Public/lib/layui-v2.6.3/css/layui.css" media="all">
    <link rel="stylesheet" href="/Public/css/public.css" media="all">
</head>
<body>
<div class="layuimini-container">
    <div class="layuimini-main">
        <div class="layui-col-xs-12">
            <div class="layui-col-xs-12" style="height: 30px;line-height: 30px;border-top:1px solid darkslategrey;"><h3>HIS项目列表</h3></div>
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">名称/输入码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="hisword" autocomplete="off" class="layui-input">
                        </div>
                    </div>
                    <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-primary"  lay-submit lay-filter="data-search-btn-his"><i class="layui-icon"></i> 搜 索</button>
                    </div>
                </div>
            </form>
            <table class="layui-hide" id="his_drug" lay-filter="currentTableFilterhis"></table>
        </div>

        <div class="layui-col-xs-12">
            <div class="layui-col-xs-12" style="height: 30px;line-height: 30px;border-top:1px solid darkslategrey;"><h3>NCC项目列表</h3></div>
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">名称/输入码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="nccword" autocomplete="off" class="layui-input">
                        </div>
                        <div class="layui-input-inline">
                            <div class="layui-input-inline">
                                <select name="is_dui_ncc">
                                    <option value="">对照状态</option>
                                    <option value="1">未对照</option>
                                    <option value="2">已对照</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-primary"  lay-submit lay-filter="data-search-btn-ncc"><i class="layui-icon"></i> 搜 索</button>

                        <button type="button" class="layui-btn layui-btn-primary" id="duizhao">对 照</button>

                        <button type="button" class="layui-btn layui-btn-primary" id="liwai">添加到例外</button>

                    </div>
                </div>
            </form>
            <table class="layui-hide" id="ncc_drug" lay-filter="currentTableFilterncc"></table>
        </div>



    </div>
</div>
<script src="/Public/lib/jquery-3.4.1/jquery-3.4.1.min.js" charset="utf-8"></script>
<script src="/Public/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'table'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table;

        table.render({
            elem: '#his_drug',
            url: '/Material/getHisList',
            cols: [[
                {type: "checkbox", width: 50},
                {field: 'material_id', width: 80, title: '药品编码',sort:true},
                {field: 'ins_code', width: 220, title: '国家药品代码'},
                {field: 'material_name', width: 200, title: '国家库注册名称'},
                {field: 'material_name', width: 200, title: '国家库商品名称'},
                {field: 'abbreviate_name', width: 135, title: '输入码'},
                {field: 'material_form_name',width:135, title: '国家库注册剂型'},
                {field: 'materialspecification', width: 135, title: '国家库注册规格'},
                {field: 'baozhuang_type', width: 135, title: '国家库包装材质'},
                {field: 'src_unit_name', width: 135, title: '国家库最小包装单位'},
                {field: 'material_producer_name', width: 250, title: '国家库药品企业'}
            ]],
            limit: 10,
            page: true,
            height:300,
            //skin: 'row',
            size: 'sm',
            //done: function (res, page, count) {
                //已对照设置当前行颜色
            //    var that = this.elem.next();
            //    res.data.forEach(function (item, index) {
            //        if (item.is_dui ==2) {
            //            var tr = that.find(".layui-table-box tbody tr[data-index='" + index + "']").css("background-color", "#EED2EE");
            //        }
            //    });
            //}
        });

        //表格渲染
        table.render({
            elem: '#ncc_drug',
            url: '/Drug/getNccList',
            cols: [[
                {type: "checkbox", width: 50},
                {field: 'id', width: 80, title: 'ID', sort: true},
                {field: 'def3', width: 250, title: '国家代码'},
                {field: 'code', width: 120, title: '输入码'},
                {field: 'name', width: 200, title: '物料名称'},
                {field: 'pk_marbasclass', width: 80, title: '物料分类'},
                {field: 'materialspec', title: '规格', width:120},
                {field: 'materialtype', width: 80, title: '型号'},
                {field: 'pk_measdoc', width: 80, title: '主计量单位'},
                {field: 'pk_mattaxes', width: 200, title: '物料税类'},
                {field: 'qualtityunit', width: 100, title: '保质期单位'},
                {field: 'qualitynum', width: 80, title: '保质期'},
                {field: 'def1', width: 250, title: '生产厂家'},
                {field: 'def2', width: 135, title: '最小包装数'},
            ]],
            height:300,
            limit: 10,
            page: true,
            size:'sm',
            //done: function (res, page, count) {
                //已对照设置当前行颜色
            //   var that = this.elem.next();
            //    res.data.forEach(function (item, index) {
            //        if (item.is_dui ==2) {
            //            var tr = that.find(".layui-table-box tbody tr[data-index='" + index + "']").css("background-color", "#EED2EE");
            //        }
            //    });
            //},
        });

        // 监听搜索操作HIS
        form.on('submit(data-search-btn-his)', function (data) {
            var result = data.field;
            //执行搜索重载
            table.reload('his_drug', {
                page: {
                    curr: 1
                }
                , where: {
                    hisword: result.hisword,
                }
            }, 'data');

            return false;
        });

        // 监听搜索操作NCC
        form.on('submit(data-search-btn-ncc)', function (data) {
            var result = data.field;
            //执行搜索重载
            table.reload('ncc_drug', {
                page: {
                    curr: 1
                }
                , where: {
                    nccword: result.nccword,
                    is_dui: result.is_dui_ncc
                }
            }, 'data');

            return false;
        });

        //项目对照操作
        $('#duizhao').click(function(){
            var ncc_checkStatus = table.checkStatus('ncc_drug');
            var ncc_codes = [];
            $(ncc_checkStatus.data).each(function (i,o){
                ncc_codes.push(o.code);
            })
            if(ncc_codes.length == 0){
                layer.msg('请选择要对照的NCC项目');
                return false;
            }else if(ncc_codes.length > 1){
                layer.msg('NCC项目只可选择一个');
                return false;
            }

            var his_checkStatus = table.checkStatus('his_drug');
            var his_ids = [];
            $(his_checkStatus.data).each(function (i,o){
                his_ids.push(o.material_id);
            })
            if(his_ids.length == 0){
                layer.msg('请选择要对照的HIS项目');
            }
            ncc_codes = ncc_codes.join(',');
            his_ids = his_ids.join(',');
            layer.load();
            $.ajax({
                url:'/Material/upMaterialDuizhao',
                type:'post',
                data:{ncc_codes:ncc_codes,his_ids:his_ids},
                dataType:'json',
                success:function(res){
                    if(res.code == 0){
                        layer.msg('操作成功',{time:1000},function(){
                            table.reload('ncc_drug');
                            table.reload('his_drug');
                            layer.closeAll();
                        })
                    }else{
                        layer.msg(res.msg);
                        layer.closeAll();
                        return false;
                    }
                }
            });
        });

        //添加到例外
        $('#liwai').click(function () {
            var ncc_checkStatus = table.checkStatus('ncc_drug');
            var ncc_codes = [];
            $(ncc_checkStatus.data).each(function (i,o){
                ncc_codes.push(o.code);
            });

            if(ncc_codes.length == 0){
                layer.msg('请选择NCC项目');
                return false;
            }

            ncc_codes = ncc_codes.join(',');
            layer.load();
            $.ajax({
                url:'/Drug/upLiwai',
                type:'post',
                data:{ncc_codes:ncc_codes},
                dataType:'json',
                success:function(res){
                    if(res.code == 0){
                        layer.msg('操作成功',{time:1000},function(){
                            table.reload('ncc_drug');
                            layer.closeAll();
                        })
                    }else{
                        layer.msg(res.msg);
                        layer.closeAll();
                        return false;
                    }
                }
            });
        })

    });

</script>

</body>
</html>