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
            <div class="layui-col-xs-12" style="height: 30px;line-height: 30px;border-top:1px solid darkslategrey;"><h3>NCC项目列表</h3></div>
            <form class="layui-form layui-form-pane" action="">
                <div class="layui-form-item">
                    <div class="layui-inline">
                        <label class="layui-form-label">名称/输入码</label>
                        <div class="layui-input-inline">
                            <input type="text" name="nccword" autocomplete="off" class="layui-input">
                        </div>
                    </div>

                    <div class="layui-inline">
                        <button type="submit" class="layui-btn layui-btn-primary"  lay-submit lay-filter="data-search-btn-ncc"><i class="layui-icon"></i> 搜 索</button>

                        <button type="button" class="layui-btn layui-btn-primary" id="liwai">取消例外</button>
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

        //表格渲染
        table.render({
            elem: '#ncc_drug',
            url: '/Drug/liwaiList',
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
            limit: 10,
            page: true,
            size:'sm',
            //done: function (res, page, count) {
            //已对照设置当前行颜色
            //    var that = this.elem.next();
            //    res.data.forEach(function (item, index) {
            //        if (item.is_dui ==2) {
            //            var tr = that.find(".layui-table-box tbody tr[data-index='" + index + "']").css("background-color", "#EED2EE");
            //        }
            //    });
            //},
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
                    nccword: result.nccword
                }
            }, 'data');

            return false;
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
                url:'/Drug/quxiaoLiwai',
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