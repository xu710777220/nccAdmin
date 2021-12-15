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

        <fieldset class="table-search-fieldset">
            <legend>搜索信息</legend>
            <div style="margin: 10px 10px 10px 10px">
                <form class="layui-form layui-form-pane" action="">
                    <div class="layui-form-item">
                        <div class="layui-inline">
                            <label class="layui-form-label">NCC编码</label>
                            <div class="layui-input-inline">
                                <input type="text" name="wuliao_code" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <label class="layui-form-label">名称/输入码</label>
                            <div class="layui-input-inline">
                                <input type="text" name="hisword" autocomplete="off" class="layui-input">
                            </div>
                        </div>
                        <div class="layui-inline">
                            <button type="submit" class="layui-btn layui-btn-primary"  lay-submit lay-filter="data-search-btn"><i class="layui-icon"></i> 搜 索</button>
                        </div>
                    </div>
                </form>
            </div>
        </fieldset>

        <table class="layui-hide" id="currentTableId" lay-filter="currentTableFilter"></table>

        <script type="text/html" id="currentTableBar">
            <a class="layui-btn layui-btn-xs layui-btn-danger data-count-delete" lay-event="delete">删除</a>
        </script>

    </div>
</div>
<script src="/Public/lib/layui-v2.6.3/layui.js" charset="utf-8"></script>
<script>
    layui.use(['form', 'table'], function () {
        var $ = layui.jquery,
            form = layui.form,
            table = layui.table;
        //表格初始化渲染
        table.render({
            elem: '#currentTableId',
            url: '/Material/materialList',
            toolbar: '#toolbarDemo',
            cols: [[
                {field: 'wuliao_code', width: 120, title: 'NCC编码'},
                {field: 'name', width: 180, title: 'NCC名称'},
                {field: 'material_name', width: 180, title: 'HIS国家药品代码'},
                {field: 'abbreviate_name', width: 120, title: 'HIS输入码'},
                {field: 'material_form_name',width:135, title: 'HIS国家库注册剂型'},
                {field: 'material_specification', width: 135, title: 'HIS国家库注册规格'},
                {field: 'baozhuang_type', width: 135, title: 'HIS国家库包装材质'},
                {field: 'src_unit_name', width: 200, title: 'HIS国家库最小包装单位'},
                {field: 'material_producer_name', width: 250, title: 'HIS国家库药品企业'},
                {field: 'user_name', width: 135, title: '操作人'},
                {field: 'create_time', width: 135, title: '操作时间'},
                {title: '操作', minWidth: 150, toolbar: '#currentTableBar', align: "center" , fixed: 'right'}
            ]],
            limits: [10, 15, 20, 25, 50, 100],
            limit: 20,
            page: true,
            size:'sm'
        });

        // 监听搜索操作
        form.on('submit(data-search-btn)', function (data) {
            var result = data.field;

            //执行搜索重载
            table.reload('currentTableId', {
                page: {
                    curr: 1
                }
                , where: {
                    wuliao_code: result.wuliao_code,
                    hisword:result.hisword
                }
            }, 'data');

            return false;
        });

        //行删除事件
        table.on('tool(currentTableFilter)', function (obj) {
            var data = obj.data;
            if (obj.event === 'delete') {
                layer.confirm('真的删除行么', function (index) {
                    $.ajax({
                        url:'/Material/delMaterial',
                        data:{id:data.id},
                        dataType:'json',
                        success:function(res){
                            if(res.code){
                                layer.msg(res.msg,{time:1000},function(){
                                    table.reload('currentTableId');
                                })
                            }
                        }
                    })
                    layer.close(index);
                });
            }
            return false;
        });

    });
</script>

</body>
</html>