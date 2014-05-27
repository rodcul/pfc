java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png ThreadsStateOverTime.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type ThreadsStateOverTime --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png BytesThroughputOverTime.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type BytesThroughputOverTime --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png HitsPerSecond.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type HitsPerSecond --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png LatenciesOverTime.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type LatenciesOverTime --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png ResponseCodesPerSecond.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type ResponseCodesPerSecond --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png ResponseTimesDistribution.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type ResponseTimesDistribution --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png ResponseTimesOverTime.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type ResponseTimesOverTime --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png ResponseTimesPercentiles.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type ResponseTimesPercentiles --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png ThroughputVsThreads.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type ThroughputVsThreads --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png TimesVsThreads.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type TimesVsThreads --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png TransactionsPerSecond.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type TransactionsPerSecond --width 1000 --height 600 --granulation 5000

java -jar ~/dev/apache-jmeter-2.11/lib/ext/CMDRunner.jar --tool Reporter --generate-png PageDataExtractorOverTime.png --input-jtl ~/dev/pfc/jmeter/results.jtl --plugin-type PageDataExtractorOverTime --width 1000 --height 600 --granulation 5000
