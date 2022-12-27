SELECT date_format(o.odr_compltime, '%M') AS date, SUM(od.odr_details_amount*od.odr_details_price) AS menu_revenue FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id WHERE o.store_id = 37 AND o.odr_status = 'CPMLT' GROUP BY YEAR(odr_compltime), Month(odr_compltime) ORDER BY (odr_compltime), Month(odr_compltime);

SELECT date_format(o.odr_compltime, '%e%b%Y') AS date, SUM(od.odr_details_amount*od.odr_details_price) AS menu_revenue FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id WHERE o.store_id = 37 AND o.odr_status = 'CMPLT' GROUP BY YEAR(odr_compltime), Month(odr_compltime), Day(odr_compltime) ORDER BY (odr_compltime) DESC LIMIT 2;

SELECT m.mitem_name AS food_name, SUM(od.odr_details_amount*od.odr_details_price) AS menu_revenue FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON m.mitem_id = od.mitem_id WHERE o.store_id = 37 AND o.odr_status = 'CMPLT' GROUP BY od.mitem_id ORDER BY menu_revenue DESC;

SELECT m.mitem_name AS Menu_Item, SUM(od.odr_details_amount) AS Total_Volume FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON m.mitem_id = od.mitem_id WHERE o.store_id = 37 AND o.odr_status = 'CMPLT' GROUP BY od.mitem_id ORDER BY Total_Volume DESC LIMIT 3;

SELECT  m.mitem_name, SUM(od.odr_detail_amount) as total FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE o.store_id = 35 AND odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('2020/6/21') AND DATE('2022/11/3')) GROUP BY mitem_name ORDER BY total DESC LIMIT 1;

SELECT EXTRACT(HOUR from o.odr_placedtime) as hr, count(*) as cnt FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE o.store_id = 35 AND odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('2020/6/21') AND DATE('2022/11/3')) GROUP BY EXTRACT(HOUR from o.odr_placedtime) ORDER BY count(*) DESC LIMIT 1;

SELECT m.mitem_name, SUM(od.odr_detail_amount) AS total_amount, SUM(od.odr_detail_amount*od.odr_detail_price) AS sub_total FROM odr o INNER JOIN odr_detail od ON o.odr_id = od.odr_id INNER JOIN mitem m ON od.mitem_id = m.mitem_id WHERE o.store_id = {$store_id} AND odr_status = 'CMPLT' AND (DATE(odr_compltime) BETWEEN DATE('{$start_date}') AND DATE('{$end_date}')) GROUP BY mitem_name ORDER BY total_amount, sub_total DESC;