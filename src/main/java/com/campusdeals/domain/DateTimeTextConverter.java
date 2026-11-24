package com.campusdeals.domain;

import jakarta.persistence.AttributeConverter;
import jakarta.persistence.Converter;

import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

/** SQLite has no date type: timestamps are stored as "yyyy-MM-dd HH:mm:ss" text. */
@Converter
public class DateTimeTextConverter implements AttributeConverter<LocalDateTime, String> {

    private static final DateTimeFormatter FORMAT = DateTimeFormatter.ofPattern("yyyy-MM-dd HH:mm:ss");

    @Override
    public String convertToDatabaseColumn(LocalDateTime value) {
        return value == null ? null : FORMAT.format(value);
    }

    @Override
    public LocalDateTime convertToEntityAttribute(String value) {
        return value == null || value.isBlank() ? null : LocalDateTime.parse(value.replace('T', ' ').substring(0, 19), FORMAT);
    }
}
