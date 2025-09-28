<?php

/**
 * Prime::create($n)
 *  - $nまでの素数の配列を返す
 * 
 * Prime::factorize($n)
 *  - $nを素因数分解する
 *  - 素数と個数の連想配列を返す
 */

class Prime {
    // $nまでの素数を生成する
    public static function create($n) {
        if ($n < 2) return [];
        if ($n == 2) return [2];
        if ($n == 3) return [2, 3];

        // ビット配列（全て 1 = 素数候補）を用意
        $size = intdiv($n, 8) + 1;
        $bits = str_repeat("\xFF", $size);

        // 0,1は素数ではない
        $bits[0] = chr(ord($bits[0]) & ~0b11);

        // 素数かどうかを判定する関数
        $isPrime = function($i) use (&$bits) {
            return (ord($bits[intdiv($i,8)]) >> ($i % 8)) & 1;
        };

        // 素数でないとマークする関数
        $unsetPrime = function($i) use (&$bits) {
            $pos = intdiv($i, 8);
            $mask = ~(1 << ($i % 8));
            $bits[$pos] = chr(ord($bits[$pos]) & $mask);
        };

        // 2の倍数を削除
        for ($i = 4; $i <= $n; $i += 2) $unsetPrime($i);
        // 3の倍数を削除
        for ($i = 9; $i <= $n; $i += 3) $unsetPrime($i);

        $sqrt = (int)sqrt($n);

        // 6n±1 でふるい
        for ($i = 5; $i <= $sqrt; $i += 6) {
            if ($isPrime($i)) {
                for ($j = $i*$i; $j <= $n; $j += $i*2) $unsetPrime($j);
            }
            $ii = $i+2;
            if ($ii <= $sqrt && $isPrime($ii)) {
                for ($j = $ii*$ii; $j <= $n; $j += $ii*2) $unsetPrime($j);
            }
        }

        // 結果を収集
        $primes = [2, 3];
        for ($i = 5; $i <= $n; $i += 2) {
            if ($isPrime($i)) $primes[] = $i;
        }
        return $primes;
    }

    // 素因数分解をする
    public static function factorize($n) {
        $res = [];

        // 2 の処理（偶数をまとめて処理）
        while ($n % 2 === 0) {
            $res[2] = ($res[2] ?? 0) + 1;
            $n /= 2;
        }

        // 3 以降は奇数だけチェック
        for ($i = 3; $i * $i <= $n; $i += 2) {
            while ($n % $i === 0) {
                $res[$i] = ($res[$i] ?? 0) + 1;
                $n /= $i;
            }
        }

        // 残りが素数ならそれを加える
        if ($n > 1) {
            $res[$n] = 1;
        }

        return $res;
    }
}

