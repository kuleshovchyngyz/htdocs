SET @pos=0;
SET @qid=0;
SELECT 
  SUM(CASE WHEN diff > 0 THEN 1 ELSE 0 END) as progress, 
  SUM(CASE WHEN diff < 0 THEN 1 ELSE 0 END) as regress,
  SUM(CASE WHEN diff = 0 THEN 1 ELSE 0 END) as not_changed
FROM 
  (
SELECT   
      id,      
      query_id, 
      region_id, 
      project_id, 
      yandex_date,
      @pos - (
        IF (
          yandex_position = '-3', 100, yandex_position
        )
      ) as diff, 
      
      @pos prev_position, 
      @pos := yandex_position curr_position,
      @qid prev_query_id,
      @qid  :=query_id as curr_query_id
    FROM 
      positions 
    WHERE 
      region_id = '16' 
      and project_id = '18' 
      AND method = 'yandex' 
      AND (
        yandex_date = '2021-07-07' 
        OR yandex_date = '2021-07-13'
      )ORDER BY positions.yandex_date DESC,
      positions.query_id  ASC  
  ) AS a WHERE   prev_query_id = curr_query_id