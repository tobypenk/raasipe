setwd("/Applications/MAMP/htdocs/raasipe/processors")
source("process.R")

cuisines_map <- c(
  "spanish"="europe",
  "american"="americas",
  "me"="westasia",
  "ancient"="ancient",
  "french"="europe",
  "indian"="westasia",
  "mexican"="americas",
  "english"="europe",
  "italian"="europe",
  "filipino"="eastasia",
  "german"="europe",
  "greek"="europe",
  "african"="africa",
  "vietnamese"="eastasia",
  "japanese"="eastasia",
  "russian"="europe",
  "serbian"="europe",
  "portugal"="europe",
  "sa"="americas",
  "chinese"="eastasia",
  "korean"="eastasia",
  "polish"="europe"
)

recipes <- execute_query(paste0(
  " select ",
  " replace(replace(r.name,'%20',' '),'%27','') recipe, ",
  " r.origin cuisine, ",
  " replace(replace(replace(replace(i.singular_name,'%20',''),'%27',''),'%E7','c'),'+','') ingredient ",
  " from recipe r join step s on s.recipe_id = r.id ",
  " join material m on m.step_id = s.id",
  " join ingredient i on m.ingredient_id = i.id",
  " where origin is not null and origin not in ('ancient','africa');"
))

df <- data.frame(cuisine=c(1),ingredient_string=c(1))
for (r in unique(recipes$recipe)) {
  tmp_df <- subset(recipes,recipe==r)
  #tmp_df$cuisine = cuisines_map[tmp_df$cuisine]
  df <- rbind(df,c(tmp_df[1,"cuisine"],paste0(tmp_df$ingredient,collapse=" ")))
}

write.csv(df[2:nrow(df),],"cuisines.csv",row.names = FALSE)
