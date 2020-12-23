## this needs to be cleaned up and saved.

execute_insert <- function(query_string) {
        
        source("credentials.R")
        
        conn <- dbConnect(MySQL(),
                          user=user,
                          password=pass,
                          dbname=dbname,
                          host=host,
                          port=port)
        #on.exit(dbDisconnect(conn))
        
        rs <- dbSendQuery(conn, query_string)
        result <- fetch(rs,n=50000)
        huh <- dbHasCompleted(rs)
        dbClearResult(rs)
        dbDisconnect(conn)
        
        1
}




revenues <- execute_query("select * from revenue;") %>%
        subset(.,year >= 2000)
names(revenues)[names(revenues) == "nominal_revenue_in_mm"] <- "revenue"
names(revenues)[names(revenues) == "drug_brand_name"] <- "drug_trade"

revenue_wide <- reshape(
        revenues,
        direction="wide",
        timevar="year",
        idvar=c("drug_trade","company","currency")
        )

names(revenue_wide) <- str_replace_all(names(revenue_wide),"\\.","_")

rows <- c()
for (i in 1:nrow(revenue_wide)) {
        rows <- c(rows,paste0("'",paste0(revenue_wide[i,],collapse="','"),"'"))
}

query <- paste0("insert into revenue_wide (",
                paste0(names(revenue_wide),collapse=","),
                ") values (",
                paste0(rows,collapse="),(")
                ,")")
execute_insert(query)


















drugs <- read.csv("/users/tobypenk/desktop/drugs.csv",stringsAsFactors = FALSE) %>%
        subset(trade != "")

insert_query <- paste0(
        "insert into drug (",
        paste0(names(drugs),collapse=","),
        ") values ('",
        apply(drugs,1,paste0,collapse="','") %>% paste0(.,collapse="'),('"),
        "');"
)

execute_insert(insert_query)







diseases <- read.csv("/users/tobypenk/desktop/diseases.csv",stringsAsFactors = FALSE) %>%
        subset(name != "")

insert_query <- paste0(
        "insert into disease (",
        paste0(names(diseases),collapse=","),
        ") values ('",
        apply(diseases,1,paste0,collapse="','") %>% paste0(.,collapse="'),('"),
        "');"
)

execute_insert(insert_query)








currency <- read.csv("/users/tobypenk/desktop/Redesign Science Market Data - currency.csv",stringsAsFactors = FALSE) %>%
        subset(.,currency != "") %>%
        arrange(.,desc(currency),desc(year))

currency$inflation_total <- NA
for (i in 1:nrow(currency)) {
        
        if (currency$year[i] == max(currency$year[currency$currency == currency$currency[i]])) {
                currency$inflation_total[i] <- 1
        } else {
                currency$inflation_total[i] <- currency$inflation_total[i-1]*(1+currency$inflation[i])
        }
}
currency$conversion_factor <- currency$exchange_to_usd * currency$inflation_total

insert_query <- paste0(
        "insert into currency (",
        paste0(names(currency),collapse=","),
        ") values ('",
        apply(currency,1,paste0,collapse="','") %>% paste0(.,collapse="'),('"),
        "');"
)

execute_insert(insert_query)












revs <- read.csv("/users/tobypenk/desktop/Redesign Science Market Data - revenue.csv",stringsAsFactors = FALSE) %>%
        subset(drug_trade != "") %>%
        subset(year >= 2014) %>%
        merge(currency,all.x=TRUE)
revs$revenue <- revs$nominal_revenue_in_mm %>%
        str_replace_all(.,",","")
        
revs$revenue <- as.numeric(revs$revenue) * revs$conversion_factor
revs <- subset(revs,select=c(drug_trade,company,revenue,year))

revs$drug_trade <- tolower(revs$drug_trade)
revs$company <- tolower(revs$company)

revenue_wide <- reshape(
        revs,
        direction="wide",
        timevar="year",
        idvar=c("drug_trade","company")
)

names(revenue_wide) <- str_replace_all(names(revenue_wide),"\\.","_")

rows <- c()
for (i in 1:nrow(revenue_wide)) {
        rows <- c(rows,paste0("'",paste0(revenue_wide[i,],collapse="','"),"'"))
}

query <- paste0("insert into revenue_wide (",
                paste0(names(revenue_wide),collapse=","),
                ") values (",
                paste0(rows,collapse="),(")
                ,")")
execute_insert(query)











