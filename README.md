# NWP-LV-1

## FYI

U kodu je ostao zakomentiran dio kojim sam kreirao bazu podataka. Zakomentirani dio koda je tu ako bude trebalo prilikom pokretanja (samo otkomentirati i ponovno pokrenuti).


## Confusing part ...

Za ekstrakciju OIB-a tvrtki iz linkova, moja logika je bila kako slijedi:

1. Dobiti link u kojemu se nalazi OIB tvrtke
2. Pošto je pattern takav da je OIB uvijek na kraju linka, ekstrahirati substring uz pomoć poznatih duljina stringova
    * OIB = 11
    * ekstenzija ***.png*** = 4 
    * ukupna duljina linka pomoću `strlen`
3. Vratiti ekstrahirani OIB kao substring


```
        function get_oib($src)
        {
                $totalLength = strlen($src);
                $oibLength = 11;
                $extensionLength = 4;
                $result = substr($src, $totalLength - ($extensionLength + $oibLength), $oibLength);
                return $result;
        }
```
