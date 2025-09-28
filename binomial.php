<?php
/**
 * Biominal::createPascalTriangle($n, $mod)
 *  - パスカルの三角形を使って$nまでのnCkを生成する
 *  - $nは5000まで800ms程度で生成可能
 * 
 * Biominal::getBinomialInit($n, $mod)
 *  - $nまでの階乗と階乗の逆元を返す
 *  - $modは素数であることが前提
 * 
 * Biominal:: getBiomial($fact, $ifact, $n, $k, $mod)
 *  - nCkを返す
 *  - getBinomialInitが実行されて結果の$fact, $ifactが渡されることが前提
 */
class Biominal {
    // $nまでのnCkを生成する
    public static function createPascalTriangle($n, $mod) {
        $combi = [];
        for ($i=0;$i<=$n;$i++) {
            $combi[$i]=array_fill(0, $i+1, 0);
            $combi[$i][0]=1;
            $combi[$i][$i]=1;
            for ($j=1;$j<$i;$j++) {
                $combi[$i][$j]=$combi[$i-1][$j-1]+$combi[$i-1][$j];
                $combi[$i][$j]%=$mod;
            }
        }
        return $combi;
    }

    /**
     * 二項係数の元を求める
     * 
     * @param Int $n 二項係数を求める上限（最大5x10^6程度）
     * @param Int $mod 余り
     *------------------------------
     * @return Array [$factorial, $ifactorial]
     * $factorial・・階乗
     * $ifactorial・・階乗の逆元 
     *
     */
    public static function getBinomialInit($n, $mod){
        $factorial = [1,1];
        $ifactorial = [1,1];
        $inv = [1,1];
        for($i=2;$i<=$n;$i++){
            $factorial[$i] = $factorial[$i-1] * $i % $mod;
            $inv[$i] = $mod - $inv[$mod%$i] * intdiv($mod, $i) % $mod;
            $ifactorial[$i] = $ifactorial[$i-1] * $inv[$i] % $mod;
        }
        return [$factorial, $ifactorial];
    }

    /**
     * nCkを求める
     * @param Array $fact 階乗
     * @param Array $ifact 階乗の逆元
     * @param Int $n nCkのn
     * @param Int $k nCkのk
     * @param Int $mod 余り
     */
    public static function getBiomial($fact, $ifact, $n, $k, $mod){
        if ($n < $k) return 0;
        if ($n < 0 || $k < 0) return 0;
        return $fact[$n] * ($ifact[$k] * $ifact[$n - $k] % $mod) % $mod;
    }
}
