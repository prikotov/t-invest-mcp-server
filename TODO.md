# TODO

Этот документ содержит задачи.


## [ ] feat: Получить фундаментальные показатели компаний

Описание: Реализовать метод [getAssetFundamentals](https://developer.tbank.ru/invest/services/instruments/methods#getassetfundamentals)
Swagger: https://russianinvestments.github.io/investAPI/swagger-ui/#/InstrumentsService/InstrumentsService_GetAssetFundamentals

Обычно данные обновляются на следующий рабочий день после публикации, в редких случаях это может занять больше недели. Информация доступна не по всем активам.

Метод возвращает все параметры контракта. Значение 0 в ответе следует приравнивать к отсутствию данных.


## [ ] feat: Получить график выплаты дивидендов по инструменту

Описание: Реализовать метод [getAssetBy](https://developer.tbank.ru/invest/services/instruments/methods#getassetby) 
Swagger: https://russianinvestments.github.io/investAPI/swagger-ui/#/InstrumentsService/InstrumentsService_GetAssetBy

Метод возвращает более подробную информацию о запрошенном активе.


## [ ] feat: Получить график выплаты дивидендов по инструменту

Описание: Реализовать метод [getDividends](https://developer.tbank.ru/invest/services/instruments/methods#getdividends)
Swagger: https://russianinvestments.github.io/investAPI/swagger-ui/#/InstrumentsService/InstrumentsService_GetDividends

Чтобы получить информацию по срокам выплаты дивидендов по инструменту, используйте метод getDividends.

Учитывайте, что входной параметр to (окончание запрашиваемого периода в часовом поясе UTC) фильтрует выходные данные по параметру record_date — дате фиксации реестра.


## [ ] feat: Получить график выплаты купонов по инструменту

Swagger: https://russianinvestments.github.io/investAPI/swagger-ui/#/InstrumentsService/InstrumentsService_GetBondCoupons


## [ ] feat: Получить расписание выхода отчетностей эмитентов

Swagger: https://russianinvestments.github.io/investAPI/swagger-ui/#/InstrumentsService/InstrumentsService_GetAssetReports

## [ ] feat: Получить сигналы

Описание: Реализовать метод [GetSignals](https://developer.tbank.ru/invest/services/signals/methods#getsignals)
Swagger: https://russianinvestments.github.io/investAPI/swagger-ui/#/SignalService/SignalService_GetSignals

Сервис сигналов поможет инвесторам найти потенциально выгодные моменты для совершения сделок на бирже или скорректировать позицию по конкретному активу, который уже есть в портфеле.

[Сигналы](https://developer.tbank.ru/invest/services/signals/head-signals)



## Посмотреть

https://developer.tbank.ru/invest/services/quotes/marketdata#gettechanalysis
