package com.campusdeals.service;

import java.text.Normalizer;
import java.util.Locale;

/** Turns "Campus Book Nook" into "campus-book-nook". */
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
