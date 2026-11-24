package com.coupondeals.repository;

import com.coupondeals.domain.Setting;
import org.springframework.data.jpa.repository.JpaRepository;

import java.util.List;

public interface SettingRepository extends JpaRepository<Setting, Long> {

    List<Setting> findAllByOrderBySortOrderAsc();
}
