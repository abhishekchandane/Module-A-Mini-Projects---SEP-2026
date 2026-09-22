SELECT 
    m.movie_title,
    ROUND(SUM(b.seats * s.price_per_seat), 2) AS total_revenue
FROM 
    bookings b
JOIN 
    screenings s ON b.screening_id = s.id
JOIN 
    movies m ON s.movie_id = m.id
WHERE 
    b.cancelled = 0
    AND s.screening_date >= '2026-03-01'
    AND s.screening_date <= '2026-03-31'
GROUP BY 
    m.id, m.movie_title
HAVING 
    SUM(b.seats * s.price_per_seat) > 500
ORDER BY 
    total_revenue DESC,
    m.movie_title ASC;
