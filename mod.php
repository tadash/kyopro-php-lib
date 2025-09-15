<?php

class Mod {
    // 拡張ユークリッド互除法
    private static function extendedGCD($a, $b) {
        if ($b == 0) {
            return [$a, 1, 0]; // gcd, x, y
        }
        [$g, $x1, $y1] = self::extendedGCD($b, $a % $b);
        $x = $y1;
        $y = $x1 - intdiv($a, $b) * $y1;
        return [$g, $x, $y];
    }

    // 逆元を求める
    public static function inverse(int $a, int $mod): ?int {
        [$g, $x, $_] = self::extendedGCD($a, $mod);
        if ($g != 1) {
            return null; // 逆元なし（a と mod が互いに素でない）
        }
        return ($x % $mod + $mod) % $mod; // 正の値に調整
    }
}
