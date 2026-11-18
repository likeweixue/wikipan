SET NAMES utf8mb4;
USE wikipan;

INSERT INTO users (id, username, email, password_hash, role, avatar_hue, created_at) VALUES
(1, 'admin',       'admin@wikipan.local', '$2y$10$1NA1/B/iY0xQFUiSjhG7re90DcMOHHRUHG8qW6eGmSu1C9y4aAMDa', 'admin', 4,   DATE_SUB(NOW(), INTERVAL 180 DAY)),
(2, '星尘资源组',   'xingchen@wikipan.local', '$2y$10$HW9o.SnCJz8G/eMeWxZaDexTKxG9znzQNdPvJiGe349MOCvYMyrSS', 'user', 268, DATE_SUB(NOW(), INTERVAL 120 DAY)),
(3, '影迷阿哲',     'azhe@wikipan.local', '$2y$10$HW9o.SnCJz8G/eMeWxZaDexTKxG9znzQNdPvJiGe349MOCvYMyrSS', 'user', 202, DATE_SUB(NOW(), INTERVAL 90 DAY)),
(4, '剧集档案馆',   'archive@wikipan.local', '$2y$10$HW9o.SnCJz8G/eMeWxZaDexTKxG9znzQNdPvJiGe349MOCvYMyrSS', 'user', 34, DATE_SUB(NOW(), INTERVAL 75 DAY)),
(5, '动漫搬运工',   'anime@wikipan.local', '$2y$10$HW9o.SnCJz8G/eMeWxZaDexTKxG9znzQNdPvJiGe349MOCvYMyrSS', 'user', 158, DATE_SUB(NOW(), INTERVAL 60 DAY));

INSERT INTO categories (id, slug, name, sort_order) VALUES
(1, 'series', '剧集', 10),
(2, 'movie', '电影', 20),
(3, 'anime', '动漫', 30),
(4, 'doc', '纪录片', 40),
(5, 'show', '综艺', 50);

INSERT INTO repositories (id, slug, title, original_title, category_id, year, description, poster, backdrop, tags, featured, heat, created_by, created_at, updated_at) VALUES
(1, 'shen-diao-ying-xiong', '神雕侠侣', 'The Return of the Condor Heroes', 1, 2006,
 '改编自金庸同名武侠小说，杨过与小龙女跨越十六年的爱恨情仇。本仓库收录 2006 版《神雕侠侣》各清晰度、各配音版本，持续补充修复与收藏资源。',
 '/assets/posters/shen-diao-ying-xiong.svg', NULL, '武侠,古装,金庸,经典', 0, 98600, 4, DATE_SUB(NOW(), INTERVAL 62 DAY), DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(2, 'san-ti', '三体', 'Three-Body', 1, 2023,
 '根据刘慈欣同名科幻小说改编，讲述地球文明与三体文明的第一次接触。仓库收录剧集全季 4K、1080P 多个版本资源，含杜比视界收藏版。',
 '/assets/posters/san-ti.svg', NULL, '科幻,刘慈欣,国产剧,高分', 1, 132400, 2, DATE_SUB(NOW(), INTERVAL 58 DAY), DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(3, 'qing-yu-nian', '庆余年', 'Joy of Life', 1, 2019,
 '范闲自澹州北上京都，在庙堂与江湖之间步步为营。收录第一季、第二季全集合集及各清晰度版本资源。',
 '/assets/posters/qing-yu-nian.svg', NULL, '古装,权谋,穿越,爽剧', 0, 118000, 4, DATE_SUB(NOW(), INTERVAL 55 DAY), DATE_SUB(NOW(), INTERVAL 1 DAY)),
(4, 'man-chang-de-ji-jie', '漫长的季节', 'The Long Season', 1, 2023,
 '东北小城桦林，一桩跨越二十年的悬案与三个老男人的追索。豆瓣高分悬疑剧，多版本高码资源归档。',
 '/assets/posters/man-chang-de-ji-jie.svg', NULL, '悬疑,东北,年代,高分', 0, 96400, 3, DATE_SUB(NOW(), INTERVAL 48 DAY), DATE_SUB(NOW(), INTERVAL 3 DAY)),
(5, 'kuang-biao', '狂飙', 'The Knockout', 1, 2023,
 '刑警安欣与黑恶势力长达二十年的较量，一部关于选择与代价的扫黑题材剧集。',
 '/assets/posters/kuang-biao.svg', NULL, '扫黑,犯罪,现实题材', 0, 88200, 3, DATE_SUB(NOW(), INTERVAL 40 DAY), DATE_SUB(NOW(), INTERVAL 4 DAY)),
(6, 'liu-lang-di-qiu-2', '流浪地球2', 'The Wandering Earth II', 2, 2023,
 '太阳危机将至，人类启动流浪地球计划。收录 4K HDR、IMAX 画幅、60 帧高码等多个版本，均可在线转存。',
 '/assets/posters/liu-lang-di-qiu-2.svg', NULL, '科幻,国产,刘慈欣,大片', 1, 152000, 2, DATE_SUB(NOW(), INTERVAL 52 DAY), DATE_SUB(NOW(), INTERVAL 6 HOUR)),
(7, 'sha-qiu-2', '沙丘2', 'Dune: Part Two', 2, 2024,
 '保罗·厄崔迪与弗雷曼人联手复仇，沙丘史诗继续。收录 IMAX 与杜比视界版本，中英双语字幕。',
 '/assets/posters/sha-qiu-2.svg', NULL, '科幻,史诗,IMAX,好莱坞', 1, 104000, 3, DATE_SUB(NOW(), INTERVAL 30 DAY), DATE_SUB(NOW(), INTERVAL 12 HOUR)),
(8, 'xiao-shen-ke-de-jiu-shu', '肖申克的救赎', 'The Shawshank Redemption', 2, 1994,
 '希望是美好的事物，也许是世上最美好的事物。经典影片 4K 修复版资源归档，含蓝光原盘。',
 '/assets/posters/xiao-shen-ke-de-jiu-shu.svg', NULL, '经典,剧情,高分,修复版', 0, 73500, 4, DATE_SUB(NOW(), INTERVAL 70 DAY), DATE_SUB(NOW(), INTERVAL 7 DAY)),
(9, 'ao-ben-hai-mo', '奥本海默', 'Oppenheimer', 2, 2023,
 '诺兰执导，原子弹之父罗伯特·奥本海默的传记史诗。收录 IMAX 与 4K HDR 版本。',
 '/assets/posters/ao-ben-hai-mo.svg', NULL, '传记,诺兰,IMAX,历史', 1, 92800, 3, DATE_SUB(NOW(), INTERVAL 26 DAY), DATE_SUB(NOW(), INTERVAL 18 HOUR)),
(10, 'gui-mie-zhi-ren', '鬼灭之刃', 'Demon Slayer', 3, 2019,
 '灶门炭治郎为救妹妹踏上斩鬼之旅。收录全四季、剧场版与无限城篇资源合集，日语原声 + 国语配音。',
 '/assets/posters/gui-mie-zhi-ren.svg', NULL, '日漫,热血,ufotable,合集', 1, 121000, 5, DATE_SUB(NOW(), INTERVAL 45 DAY), DATE_SUB(NOW(), INTERVAL 8 HOUR)),
(11, 'jin-ji-de-ju-ren', '进击的巨人', 'Attack on Titan', 3, 2013,
 '墙壁之外是巨人，也是真相。全四季 + 最终季完结篇资源归档，含无修版与高码收藏版。',
 '/assets/posters/jin-ji-de-ju-ren.svg', NULL, '日漫,热血,神作,完结', 1, 113000, 5, DATE_SUB(NOW(), INTERVAL 50 DAY), DATE_SUB(NOW(), INTERVAL 2 DAY)),
(12, 'lan-se-xing-qiu', '蓝色星球', 'The Blue Planet', 4, 2001,
 'BBC 自然纪录片经典，深入海洋的每一个角落。收录 4K 修复版与国语配音版本。',
 '/assets/posters/lan-se-xing-qiu.svg', NULL, 'BBC,自然,海洋,经典', 0, 42900, 2, DATE_SUB(NOW(), INTERVAL 35 DAY), DATE_SUB(NOW(), INTERVAL 9 DAY)),
(13, 'feng-wei-ren-jian', '风味人间', 'Once Upon a Bite', 4, 2018,
 '陈晓卿团队打造的美食纪录片，跨越山海寻找人间风味。全三季合集，4K 高码版本。',
 '/assets/posters/feng-wei-ren-jian.svg', NULL, '美食,陈晓卿,人文,下饭', 0, 51800, 4, DATE_SUB(NOW(), INTERVAL 28 DAY), DATE_SUB(NOW(), INTERVAL 5 DAY)),
(14, 'yue-dui-de-xia-tian', '乐队的夏天', 'The Big Band', 5, 2019,
 '中国独立乐队竞演综艺，收录全三季 4K 与高码版本，重温现场的高光时刻。',
 '/assets/posters/yue-dui-de-xia-tian.svg', NULL, '音乐,乐队,现场,综艺', 0, 39600, 5, DATE_SUB(NOW(), INTERVAL 20 DAY), DATE_SUB(NOW(), INTERVAL 6 DAY));

INSERT INTO resources (id, repository_id, title, quality, netdisk, share_url, access_code, size, subtitle, episodes, note, votes, views, publisher_id, created_at) VALUES
(1, 1, '神雕侠侣 2006 4K 修复版 全40集 国语中字 杜比全景声', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-sd4k', '8fs3', '186.4 GB', '国语 / 中字', '全40集', 'H265 编码，杜比全景声音轨，收藏首选', 412, 2860, 4, DATE_SUB(NOW(), INTERVAL 26 HOUR)),
(2, 1, '神雕侠侣 2006 1080P 全40集 国粤双语 简繁中字', '1080P', '115', 'https://115.com/s/wikipan-sd1080', 'a7k2', '92.6 GB', '国粤双语 / 简繁中字', '全40集', '经典台配版本，画质干净', 268, 1740, 3, DATE_SUB(NOW(), INTERVAL 0 DAY)),
(3, 1, '神雕侠侣 2006 2160P 高码收藏版 全40集 H265', '2160P', 'aliyun', 'https://www.alipan.com/s/wikipan-sd2160', '9dj4', '320.8 GB', '国语 / 中字', '全40集', '单集约 8GB，适合大屏观影', 96, 620, 2, DATE_SUB(NOW(), INTERVAL 12 DAY)),
(4, 1, '神雕侠侣 2006 720P 全40集 国语无字 精简版', '720P', 'baidu', 'https://pan.baidu.com/s/1wikipansd720', 'k3m9', '38.2 GB', '国语 / 无字', '全40集', '小体积版本，移动端观看', 41, 320, 3, DATE_SUB(NOW(), INTERVAL 21 DAY)),

(5, 2, '三体 2023 4K HDR 全30集 国语中字 杜比视界', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-st4k', '6tq8', '168.2 GB', '国语 / 中字', '全30集', '杜比视界 + 全景声，官方压制', 532, 3680, 2, DATE_SUB(NOW(), INTERVAL 4 HOUR)),
(6, 2, '三体 2023 1080P 全30集 国语中字', '1080P', 'aliyun', 'https://www.alipan.com/s/wikipan-st1080', 'p2w7', '76.5 GB', '国语 / 中字', '全30集', '通用版本，兼容性好', 301, 2190, 4, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(7, 2, '三体 2023 4K 导演剪辑版 全30集 高码', '4K', '115', 'https://115.com/s/wikipan-stdc', 'r5f1', '245.1 GB', '国语 / 中字', '全30集', '收录未删减片段，单集 8GB+', 142, 980, 3, DATE_SUB(NOW(), INTERVAL 9 DAY)),
(8, 2, '三体 2023 1080P 无广告纯净版 全30集', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipanst', 'b8n4', '68.9 GB', '国语 / 中字', '全30集', '去片头片尾广告', 77, 530, 5, DATE_SUB(NOW(), INTERVAL 15 DAY)),

(9, 3, '庆余年 全46集 4K 国语中字 第一季+第二季', '4K', '115', 'https://115.com/s/wikipan-qyn4k', 'y6c2', '210.3 GB', '国语 / 中字', '全46集', '两季连播，顺序整理完毕', 486, 3120, 4, DATE_SUB(NOW(), INTERVAL 7 HOUR)),
(10, 3, '庆余年 全46集 1080P 国粤双语 简繁中字', '1080P', 'quark', 'https://pan.quark.cn/s/wikipan-qyn1080', 'h4z8', '88.4 GB', '国粤双语 / 简繁中字', '全46集', 'TVB 粤语配音版本', 265, 1760, 3, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(11, 3, '庆余年 第一季+第二季 2160P 高码合集', '2160P', 'aliyun', 'https://www.alipan.com/s/wikipan-qyn2160', 'm1v5', '402.6 GB', '国语 / 中字', '全46集', '高码率收藏，附花絮', 118, 720, 2, DATE_SUB(NOW(), INTERVAL 16 DAY)),

(12, 4, '漫长的季节 4K 全12集 国语中字 杜比音效', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-mcdjj4k', 'q9s6', '62.8 GB', '国语 / 中字', '全12集', '豆瓣 9.4，画质极佳', 356, 2410, 3, DATE_SUB(NOW(), INTERVAL 11 HOUR)),
(13, 4, '漫长的季节 1080P 全12集 国语中字', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipanmcdjj', 'd3x7', '28.4 GB', '国语 / 中字', '全12集', '轻量版本，手机可存', 189, 1240, 4, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(14, 4, '漫长的季节 4K HDR 全12集 高码收藏版', '4K', '115', 'https://115.com/s/wikipan-mcdjjhdr', 'w2e9', '98.2 GB', '国语 / 中字', '全12集', 'HDR10 版本，暗部细节更丰富', 84, 560, 2, DATE_SUB(NOW(), INTERVAL 20 DAY)),

(15, 5, '狂飙 4K 全39集 国语中字', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-kb4k', 't7r4', '198.4 GB', '国语 / 中字', '全39集', '完整未删减版本', 402, 2680, 3, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(16, 5, '狂飙 1080P 全39集 国语中字', '1080P', 'aliyun', 'https://www.alipan.com/s/wikipan-kb1080', 'g5h1', '88.1 GB', '国语 / 中字', '全39集', '标准码率，转存即看', 233, 1530, 4, DATE_SUB(NOW(), INTERVAL 6 DAY)),
(17, 5, '狂飙 4K 杜比视界 全39集 高码', '4K', '115', 'https://115.com/s/wikipan-kbdv', 'n8j3', '260.5 GB', '国语 / 中字', '全39集', '杜比视界 + 杜比音效', 96, 610, 2, DATE_SUB(NOW(), INTERVAL 17 DAY)),

(18, 6, '流浪地球2 4K HDR 国语中字 IMAX 画幅', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-ll2imax', 'c4k8', '32.6 GB', '国语 / 中字', '正片', 'IMAX 满画幅，杜比全景声', 689, 4820, 2, DATE_SUB(NOW(), INTERVAL 2 HOUR)),
(19, 6, '流浪地球2 2160P 60帧 高码 国英双语', '2160P', '115', 'https://115.com/s/wikipan-ll260', 'v6b2', '51.2 GB', '国英双语 / 中英双字', '正片', '60 帧插帧版本，动作更顺滑', 402, 2760, 3, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(20, 6, '流浪地球2 1080P 国语中字 高清', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipanll2', 's9d5', '9.8 GB', '国语 / 中字', '正片', '体积小，下载快', 268, 1830, 4, DATE_SUB(NOW(), INTERVAL 8 DAY)),
(21, 6, '流浪地球2 4K 导演剪辑加长版', '4K', 'aliyun', 'https://www.alipan.com/s/wikipan-ll2dc', 'f1g7', '45.7 GB', '国语 / 中字', '正片', '含 12 分钟未公映片段', 188, 1120, 5, DATE_SUB(NOW(), INTERVAL 13 DAY)),

(22, 7, '沙丘2 4K HDR 国英双语 杜比全景声', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-dune2', 'j7u4', '28.4 GB', '国英双语 / 中英双字', '正片', '杜比视界版本，沙漠场景极佳', 411, 2940, 3, DATE_SUB(NOW(), INTERVAL 9 HOUR)),
(23, 7, '沙丘2 2160P IMAX 版 中英双字', '2160P', '115', 'https://115.com/s/wikipan-dune2imax', 'e2q6', '36.8 GB', '英语 / 中英双字', '正片', 'IMAX 1.90:1 画幅', 276, 1680, 2, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(24, 7, '沙丘2 1080P 中英双字 高清', '1080P', 'aliyun', 'https://www.alipan.com/s/wikipan-dune21080', 'l8z3', '8.6 GB', '英语 / 中英双字', '正片', '轻量版本，适合移动端', 152, 970, 4, DATE_SUB(NOW(), INTERVAL 11 DAY)),

(25, 8, '肖申克的救赎 4K 修复版 国英双语 中英双字', '4K', '115', 'https://115.com/s/wikipan-shawshank4k', 'u5a9', '24.8 GB', '国英双语 / 中英双字', '正片', '4K 修复，附导演评论音轨', 388, 2470, 4, DATE_SUB(NOW(), INTERVAL 3 DAY)),
(26, 8, '肖申克的救赎 1080P 蓝光原盘 中英双字', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipanshaw', 'i3o7', '8.2 GB', '英语 / 中英双字', '正片', '蓝光原盘直压，无二次压缩', 214, 1380, 3, DATE_SUB(NOW(), INTERVAL 9 DAY)),
(27, 8, '肖申克的救赎 2160P HDR10 收藏版', '2160P', 'aliyun', 'https://www.alipan.com/s/wikipan-shaw2160', 'o6p2', '31.5 GB', '英语 / 中英双字', '正片', 'HDR10 + DTS-HD 音轨', 137, 860, 2, DATE_SUB(NOW(), INTERVAL 22 DAY)),

(28, 9, '奥本海默 4K HDR 国英双语 IMAX', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-oppen4k', 'b7c5', '42.1 GB', '国英双语 / 中英双字', '正片', 'IMAX 画幅，诺兰胶片质感', 468, 3260, 3, DATE_SUB(NOW(), INTERVAL 6 HOUR)),
(29, 9, '奥本海默 2160P 高码 中英双字', '2160P', '115', 'https://115.com/s/wikipan-oppen2160', 'z4n8', '58.4 GB', '英语 / 中英双字', '正片', '高码率版本，细节丰富', 222, 1420, 2, DATE_SUB(NOW(), INTERVAL 5 DAY)),
(30, 9, '奥本海默 1080P 中英双字 高清', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipanop', 'x9m1', '12.8 GB', '英语 / 中英双字', '正片', '标准版本，兼容所有播放器', 143, 930, 4, DATE_SUB(NOW(), INTERVAL 14 DAY)),

(31, 10, '鬼灭之刃 全系列 4K 国语+日语 简繁中字', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-kimetsu4k', 'a2t6', '286.4 GB', '国语+日语 / 简繁中字', '全四季', '含剧场版与无限列车篇', 517, 3540, 5, DATE_SUB(NOW(), INTERVAL 8 HOUR)),
(32, 10, '鬼灭之刃 1080P 全四季+剧场版 合集', '1080P', '115', 'https://115.com/s/wikipan-kimetsu1080', 'r7y4', '158.2 GB', '日语 / 简繁中字', '全四季', '按季整理，附番外', 366, 2490, 5, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(33, 10, '鬼灭之刃 无限城篇 剧场版 2160P 高码', '2160P', 'aliyun', 'https://www.alipan.com/s/wikipan-kimetsuinf', 'k3w9', '48.6 GB', '日语 / 简繁中字', '剧场版', '影院版高码资源', 274, 1810, 2, DATE_SUB(NOW(), INTERVAL 7 DAY)),
(34, 10, '鬼灭之刃 1080P 粤语配音版 全四季', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipankimetsu', 'g6s2', '96.4 GB', '粤语 / 繁中字', '全四季', 'TVB 粤语配音', 88, 570, 5, DATE_SUB(NOW(), INTERVAL 19 DAY)),

(35, 11, '进击的巨人 全四季+最终季 4K 日语中字', '4K', '115', 'https://115.com/s/wikipan-aot4k', 'n4d7', '318.6 GB', '日语 / 简繁中字', '全集', '含最终季完结篇前后篇', 489, 3310, 5, DATE_SUB(NOW(), INTERVAL 10 HOUR)),
(36, 11, '进击的巨人 1080P 全集 简繁中字 无修版', '1080P', 'quark', 'https://pan.quark.cn/s/wikipan-aot1080', 'h8v2', '168.3 GB', '日语 / 简繁中字', '全集', '无修完整版，未删减', 322, 2140, 5, DATE_SUB(NOW(), INTERVAL 4 DAY)),
(37, 11, '进击的巨人 最终季 完结篇 2160P 高码', '2160P', 'aliyun', 'https://www.alipan.com/s/wikipan-aotfinal', 'm5c9', '88.5 GB', '日语 / 简繁中字', '完结篇', '高码 HDR 版本', 176, 1150, 2, DATE_SUB(NOW(), INTERVAL 16 DAY)),

(38, 12, '蓝色星球 4K 全11集 国语配音 中字', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-blueplanet', 'p3k8', '96.4 GB', '国语 / 中字', '全11集', 'BBC 4K 修复 + 央视配音', 198, 1420, 2, DATE_SUB(NOW(), INTERVAL 1 DAY)),
(39, 12, '蓝色星球 1080P 全11集 国英双语', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipanblue', 'd9f4', '32.8 GB', '国英双语 / 中字', '全11集', '经典 BBC 版本', 121, 830, 4, DATE_SUB(NOW(), INTERVAL 12 DAY)),
(40, 12, '蓝色星球 2160P HDR 全11集 收藏版', '2160P', '115', 'https://115.com/s/wikipan-blue4k', 's2l6', '128.2 GB', '英语 / 中字', '全11集', 'HDR 收藏版，附幕后花絮', 96, 640, 2, DATE_SUB(NOW(), INTERVAL 24 DAY)),

(41, 13, '风味人间 全三季 4K 国语中字', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-food4k', 'y7b3', '158.6 GB', '国语 / 中字', '全三季', '陈晓卿作品合集', 302, 2010, 4, DATE_SUB(NOW(), INTERVAL 5 HOUR)),
(42, 13, '风味人间 全三季 1080P 国语中字', '1080P', 'aliyun', 'https://www.alipan.com/s/wikipan-food1080', 'c1r8', '68.4 GB', '国语 / 中字', '全三季', '下饭神器，轻量版本', 187, 1260, 5, DATE_SUB(NOW(), INTERVAL 8 DAY)),
(43, 13, '风味人间 第一季+第二季 2160P 高码', '2160P', '115', 'https://115.com/s/wikipan-food2160', 'w4m9', '112.8 GB', '国语 / 中字', '前两季', '高码收藏版本', 74, 490, 2, DATE_SUB(NOW(), INTERVAL 18 DAY)),

(44, 14, '乐队的夏天 全三季 4K 国语中字', '4K', 'quark', 'https://pan.quark.cn/s/wikipan-band4k', 'q8k5', '246.8 GB', '国语 / 中字', '全三季', '含全部现场纯享版', 208, 1390, 5, DATE_SUB(NOW(), INTERVAL 2 DAY)),
(45, 14, '乐队的夏天 全三季 1080P 国语中字', '1080P', 'baidu', 'https://pan.baidu.com/s/1wikipanband', 't5g1', '98.2 GB', '国语 / 中字', '全三季', '标准版，转存即看', 156, 1030, 3, DATE_SUB(NOW(), INTERVAL 10 DAY)),
(46, 14, '乐队的夏天 第三季 2160P 高码 杜比音效', '2160P', 'aliyun', 'https://www.alipan.com/s/wikipan-band3', 'j2p7', '88.6 GB', '国语 / 中字', '第三季', '杜比音效，现场感强', 61, 420, 2, DATE_SUB(NOW(), INTERVAL 22 DAY));
