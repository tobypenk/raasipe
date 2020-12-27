library(RMySQL)
library(magrittr)

execute_query <- function(query_string) {
        
        source("credentials.R")
        
        conn <- dbConnect(MySQL(),
                          user=user,
                          password=pass,
                          dbname=dbname,
                          host=host,
                          port=port)
        
        rs <- dbSendQuery(conn, query_string)
        result <- fetch(rs,n=50000)
        huh <- dbHasCompleted(rs)
        dbClearResult(rs)
        dbDisconnect(conn)
        
        result
}