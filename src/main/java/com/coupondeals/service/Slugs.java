package com.coupondeals.service;

import java.text.Normalizer;
import java.util.Locale;

/** Turns "Urban Threads" into "urban-threads". */
public final class Slugs {

    private Slugs() {
    }

    public static String of(String text) {
        if (text == null) {
            return "";
        }
        return Normalizer.normalize(text, Normalizer.Form.NFKD)
                .replaceAll("\\p{M}", "")
                .toLowerCase(Locale.ROOT)
                .replaceAll("[^a-z0-9]+", "-")
                .replaceAll("^-+|-+$", "");
    }
}
