##
药品对照
耗材对照
药品对照记录（删除对照）
耗材对照记录（删除对照）

ALTER TABLE `t_drug`
ADD COLUMN `is_dui` tinyint(1) NULL DEFAULT 1 COMMENT '是否对照（1未对照，2对照）' AFTER `ins_code`;

ALTER TABLE `nccloud`.`ncc_wuliao`
ADD COLUMN `is_dui` tinyint(1) NULL DEFAULT 1 COMMENT '是否对照（1未对照2对照）' AFTER `materialconvert`;

ALTER TABLE t_material`
ADD COLUMN `is_dui` tinyint(1) NULL DEFAULT 1 COMMENT '是否对照（1未对照2对照）' AFTER `ins_code`;