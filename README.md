# 題目一
請寫出一條查詢語句(SQL)，列出在2023年5月下訂的訂單，使用台幣付款且5月總金額最
多的前10筆的旅宿ID(bnb_id),旅宿名稱(bnb_name),5月總金額 (may_amount)

``` sql
    SELECT
        orders.bnb_id,
        bnbs.name AS bnb_name,
        SUM(orders.mount) AS may_amount
    FROM
        orders
        JOIN bnbs ON orders.bnb_id = bnbs.id
    WHERE
        orders.currency = 'TWD'
        AND orders.created_at >= '2023-05-01'
        AND orders.created_at < '2023-06-01'
    GROUP BY
        orders.bnb_id,
        bnbs.name
    ORDER BY
        may_amount DESC
    LIMIT 10;
        
```

# 題目二
在題目一的執行下，我們發現SQL執行速度很慢，您會怎麼去優化？請闡述您怎麼判斷與優
化的方式

1. 用 `EXPLAIN` 來看 SQL 查詢的細節來分析是否有用 idx 或是否使用適合的 JOIN。
2. 利用索引 idx 方式來增加效能
``` sql
CREATE INDEX idx_orders_currency_created_at ON orders(currency, created_at);

CREATE INDEX idx_orders_room_id ON orders(room_id);
CREATE INDEX idx_rooms_bnb_id ON rooms(bnb_id);
```
3. 如果量很大可以用臨時表搭配 Batch Processing 來處理，比如每 7 天 select 並寫入臨時表。